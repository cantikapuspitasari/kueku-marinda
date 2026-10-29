<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Klik tombol Google -> lempar ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google mengembalikan pengguna ke sini
    public function callback()
    {
        try {
            $g = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Masuk dengan Google gagal. Silakan coba lagi.']);
        }

        $email = $g->getEmail();
        if (!$email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Google tidak menyediakan email.']);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Email ini milik pengelola -> tidak boleh lewat portal pelanggan
            if ($user->role !== 'PEMBELI') {
                return redirect()->route('login')->withErrors([
                    'email' => 'Anda tidak memiliki akses ke halaman ini. Akun pengelola silakan masuk lewat halaman Masuk Pengelola.',
                ]);
            }
            if (($user->status_akun ?? 'AKTIF') === 'NONAKTIF') {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi pemilik toko.']);
            }
        } else {
            // Pengguna baru: daftar otomatis sebagai PEMBELI (tersimpan di database)
            $user = (new User())->forceFill([
                'nama'        => $g->getName() ?: Str::before($email, '@'),
                'email'       => $email,
                'password'    => Hash::make(Str::random(40)), // acak: login hanya lewat Google
                'no_telepon'  => '',
                'role'        => 'PEMBELI',
                'status_akun' => 'AKTIF',
            ]);
            $user->save();
        }

        // Pastikan ada baris di tabel pembeli (dipakai saat checkout)
        if (!DB::table('pembeli')->where('email', $user->email)->exists()) {
            DB::table('pembeli')->insert([
                'nama'       => $user->nama,
                'email'      => $user->email,
                'password'   => $user->password,
                'no_telepon' => $user->no_telepon ?? '',
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended('/produk');
    }
}
