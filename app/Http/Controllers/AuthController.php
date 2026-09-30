<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    // S2-05: validasi dipindahkan ke LoginRequest
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek status akun, kalau nonaktif langsung logout paksa
            if (Auth::user()->status_akun === 'NONAKTIF') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan']);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    // Tampilkan form registrasi (khusus Pembeli)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi (role otomatis PEMBELI, sesuai use case awal)
    // S2-05: validasi dipindahkan ke RegisterRequest
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'nama'       => $validated['nama'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'no_telepon' => $validated['no_telepon'],
            'role'       => 'PEMBELI',
            'status_akun'=> 'AKTIF',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registrasi berhasil, selamat datang!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}