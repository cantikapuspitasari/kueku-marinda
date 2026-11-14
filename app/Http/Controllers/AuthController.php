<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\User;
use App\Support\Telepon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const PERAN_STAFF = ['ADMIN', 'OWNER', 'KURIR'];

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginStaff()
    {
        return view('auth.login-staff');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Login pelanggan menggunakan guard pembeli
    public function login(Request $request)
    {
        return $this->prosesLogin($request, 'pembeli');
    }

    // Login staf menggunakan guard web
    public function loginStaff(Request $request)
    {
        return $this->prosesLogin($request, 'web');
    }

    private function prosesLogin(Request $request, string $guardName)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $guard = Auth::guard($guardName);

        if (!$guard->attempt($data, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau kata sandi salah.'
                ]);
        }

        $akun = $guard->user();

        if (($akun->status_akun ?? 'AKTIF') === 'NONAKTIF') {
            $this->keluar($request, $guardName);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi pengelola toko.'
                ]);
        }

        // Validasi peran khusus akun staf
        if (
            $guardName === 'web' &&
            !in_array($akun->role, self::PERAN_STAFF, true)
        ) {
            $this->keluar($request, $guardName);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun pelanggan silakan masuk melalui halaman pelanggan.'
                ]);
        }

        $request->session()->regenerate();

        if ($guardName === 'pembeli') {
            return redirect()->intended('/produk');
        }

        return match ($akun->role) {
            'ADMIN' => redirect()->route('admin.dashboard'),
            'OWNER' => redirect()->route('owner.dashboard'),
            'KURIR' => redirect()->route('pengiriman.index'),
            default => redirect('/staff/login'),
        };
    }

    // Registrasi pelanggan ke tabel pembeli
    public function register(Request $request)
    {
        $nama = $request->input('nama', $request->input('name'));
        $request->merge(['nama' => $nama]);

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pembeli,email',
            'no_telepon' => 'required|string|min:9|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar. Silakan masuk.',
            'no_telepon.required' => 'No. telepon wajib diisi.',
            'no_telepon.min' => 'No. telepon minimal 9 digit.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        $pembeli = Pembeli::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telepon' => Telepon::normalisasi($data['no_telepon']),
            'password' => Hash::make($data['password']),
            'status_akun' => 'AKTIF',
        ]);

        Auth::guard('pembeli')->login($pembeli);

        $request->session()->regenerate();

        return redirect('/produk')
            ->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }

    // Logout pelanggan
    public function logout(Request $request)
    {
        $this->keluar($request, 'pembeli');

        return redirect()->route('home');
    }

    // Logout staf
    public function logoutStaff(Request $request)
    {
        $this->keluar($request, 'web');

        return redirect('/staff/login');
    }

    private function keluar(Request $request, string $guardName): void
    {
        Auth::guard($guardName)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
