<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Daftar semua akun Admin (untuk Owner)
    public function index()
    {
        $admins = User::where('role', 'ADMIN')->get();
        return view('user.index', compact('admins'));
    }

    // Form tambah akun Admin baru
    public function create()
    {
        return view('user.create');
    }

    // Simpan akun Admin baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'no_telepon' => 'required|string|max:20',
        ]);

        User::create([
            'nama'        => $validated['nama'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'no_telepon'  => $validated['no_telepon'],
            'role'        => 'ADMIN',
            'status_akun' => 'AKTIF',
        ]);

        return redirect()->route('user.index')->with('success', 'Akun Admin berhasil ditambahkan');
    }

    // Nonaktifkan akun Admin
    public function nonaktifkan($id)
    {
        $user = User::where('role', 'ADMIN')->findOrFail($id);
        $user->update(['status_akun' => 'NONAKTIF']);

        return back()->with('success', 'Akun Admin berhasil dinonaktifkan');
    }

    // Aktifkan kembali akun Admin
    public function aktifkan($id)
    {
        $user = User::where('role', 'ADMIN')->findOrFail($id);
        $user->update(['status_akun' => 'AKTIF']);

        return back()->with('success', 'Akun Admin berhasil diaktifkan');
    }
}