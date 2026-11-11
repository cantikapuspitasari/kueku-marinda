<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\User;
use App\Support\Telepon;
=======
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
>>>>>>> origin/feature/backend
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Peran yang boleh masuk lewat tiap portal
    private const PERAN_PELANGGAN = ['PEMBELI'];
    private const PERAN_STAFF     = ['ADMIN', 'OWNER', 'KURIR'];
=======
use App\Models\User;
use App\Models\Pembeli;

class AuthController extends Controller
{
    // ================= PEMBELI (guard: pembeli) =================
>>>>>>> origin/feature/backend

    public function showLogin()
    {
        return view('auth.login');
    }

<<<<<<< HEAD
=======
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Status AKTIF menjadi syarat login.
        // Akun NONAKTIF tidak akan dibuatkan sesi.
        if (Auth::guard('pembeli')->attempt($credentials + ['status_akun' => 'AKTIF'])) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // Beritahu "dinonaktifkan" hanya kalau password benar.
        $akun = Pembeli::where('email', $credentials['email'])->first();

        if (
            $akun &&
            $akun->status_akun === 'NONAKTIF' &&
            Hash::check($credentials['password'], $akun->password)
        ) {
            return back()
                ->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan'
                ])
                ->onlyInput('email');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah'
            ])
            ->onlyInput('email');
    }

>>>>>>> origin/feature/backend
    public function showRegister()
    {
        return view('auth.register');
    }

<<<<<<< HEAD
    // ---------------------------------------------------------------
    // LOGIN (dipakai bersama oleh Portal Pelanggan dan Portal Pengelola)
    // Field tersembunyi "portal" menentukan pintu mana yang dipakai.
    // ---------------------------------------------------------------
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $portal = $request->input('portal') === 'staff' ? 'staff' : 'pelanggan';

        if (!Auth::attempt($data, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        $user = Auth::user();

        // Akun dinonaktifkan oleh Owner
        if (($user->status_akun ?? 'AKTIF') === 'NONAKTIF') {
            $this->keluar($request);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi pemilik toko.']);
        }

        // Cek kesesuaian peran dengan portal yang dipakai
        $boleh = $portal === 'staff' ? self::PERAN_STAFF : self::PERAN_PELANGGAN;
        if (!in_array($user->role, $boleh, true)) {
            $this->keluar($request);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini. '
                    . ($portal === 'staff'
                        ? 'Akun pelanggan silakan masuk lewat halaman Masuk pelanggan.'
                        : 'Akun pengelola silakan masuk lewat halaman Masuk Pengelola.')]);
        }

        $request->session()->regenerate();

        // Pastikan pelanggan punya baris di tabel pembeli (dipakai saat checkout)
        if ($user->role === 'PEMBELI') {
            $this->pastikanPembeli($user);
        }

        return match ($user->role) {
            'ADMIN' => redirect()->route('admin.dashboard'),
            'OWNER' => redirect()->route('owner.dashboard'),
            'KURIR' => redirect('/pengiriman'),
            default => redirect()->intended('/produk'),
        };
    }

    // ---------------------------------------------------------------
    // REGISTER (hanya untuk pelanggan; tersimpan ke database)
    // ---------------------------------------------------------------
    public function register(Request $request)
    {
        $nama = $request->input('nama', $request->input('name'));
        $request->merge(['nama' => $nama]);

        $data = $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:100|unique:users,email',
            'no_telepon'            => 'required|string|min:9|max:20',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'nullable|same:password',
        ], [
            'email.unique' => 'Email sudah terdaftar. Silakan masuk.',
            'no_telepon.required' => 'No. telepon wajib diisi.',
            'no_telepon.min' => 'No. telepon minimal 9 digit.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password_confirmation.same' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        $user = (new User())->forceFill([
            'nama'        => $data['nama'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'no_telepon'  => Telepon::normalisasi($data['no_telepon']),
            'role'        => 'PEMBELI',
            'status_akun' => 'AKTIF',
        ]);
        $user->save();

        $this->pastikanPembeli($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/produk')->with('success', 'Pendaftaran berhasil. Selamat datang!');
=======
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $pembeli = Pembeli::create([
            'nama'        => $validated['nama'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'no_telepon'  => $validated['no_telepon'],
            'status_akun' => 'AKTIF',
        ]);

        Auth::guard('pembeli')->login($pembeli);

        return redirect('/')
            ->with('success', 'Registrasi berhasil, selamat datang!');
>>>>>>> origin/feature/backend
    }

    public function logout(Request $request)
    {
<<<<<<< HEAD
        $this->keluar($request);
        return redirect()->route('home');
    }

    // ---------- helper ----------
    private function keluar(Request $request): void
    {
        Auth::logout();
=======
        Auth::guard('pembeli')->logout();

>>>>>>> origin/feature/backend
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

<<<<<<< HEAD
    private function pastikanPembeli($user): void
    {
        $ada = DB::table('pembeli')->where('email', $user->email)->exists();
        if (!$ada) {
            DB::table('pembeli')->insert([
                'nama'       => $user->nama,
                'email'      => $user->email,
                'password'   => $user->password,
                'no_telepon' => $user->no_telepon ?? '',
            ]);
        }
    }
}
=======

    // ================= STAF: Admin/Owner/Kurir (guard: web) =================

    public function showLoginStaff()
    {
        return view('auth.login-staff');
    }

    public function loginStaff(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Status AKTIF menjadi syarat login.
        // Akun NONAKTIF tidak akan dibuatkan sesi.
        if (Auth::guard('web')->attempt($credentials + ['status_akun' => 'AKTIF'])) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // Beritahu "dinonaktifkan" hanya kalau password benar.
        $akun = User::where('email', $credentials['email'])->first();

        if (
            $akun &&
            $akun->status_akun === 'NONAKTIF' &&
            Hash::check($credentials['password'], $akun->password)
        ) {
            return back()
                ->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan'
                ])
                ->onlyInput('email');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah'
            ])
            ->onlyInput('email');
    }

    public function logoutStaff(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/staff/login');
    }
}
>>>>>>> origin/feature/backend
