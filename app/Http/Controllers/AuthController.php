<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pembeli;

class AuthController extends Controller
{
    // ================= PEMBELI (guard: pembeli) =================

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('pembeli')->attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::guard('pembeli')->user()->status_akun === 'NONAKTIF') {
                Auth::guard('pembeli')->logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan']);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $pembeli = Pembeli::create([
            'nama'       => $validated['nama'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'no_telepon' => $validated['no_telepon'],
            'status_akun'=> 'AKTIF',
        ]);

        Auth::guard('pembeli')->login($pembeli);

        return redirect('/')->with('success', 'Registrasi berhasil, selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::guard('pembeli')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ================= STAF: Admin/Owner/Kurir (guard: web) =================

    public function showLoginStaff()
    {
        return view('auth.login-staff');
    }

    public function loginStaff(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::guard('web')->user()->status_akun === 'NONAKTIF') {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan']);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logoutStaff(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/staff/login');
    }
}