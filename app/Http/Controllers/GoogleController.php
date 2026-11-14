<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Mengarahkan pengguna ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Masuk dengan Google gagal. Silakan coba lagi.'
                ]);
        }

        $email = $googleUser->getEmail();

        if (!$email) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun Google tidak menyediakan email.'
                ]);
        }

        // Cegah akun staff masuk melalui portal pelanggan
        $staff = User::where('email', $email)->first();

        if ($staff) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun pengelola silakan masuk melalui halaman Masuk Pengelola.'
                ]);
        }

        // Cari akun pelanggan berdasarkan email Google
        $pembeli = Pembeli::where('email', $email)->first();

        // Jika belum ada, buat akun pelanggan baru
        if (!$pembeli) {
            $pembeli = Pembeli::create([
                'nama' => $googleUser->getName()
                    ?: Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'no_telepon' => '',
                'status_akun' => 'AKTIF',
            ]);
        }

        // Periksa status akun pelanggan
        if (($pembeli->status_akun ?? 'AKTIF') === 'NONAKTIF') {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi pemilik toko.'
                ]);
        }

        // Login menggunakan guard pelanggan
        Auth::guard('pembeli')->login($pembeli);

        request()->session()->regenerate();

        return redirect()->intended('/produk');
    }
}
