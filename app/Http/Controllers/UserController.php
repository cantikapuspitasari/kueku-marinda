<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Daftar semua akun staf (Admin, Owner, Kurir) - untuk Owner
    public function index()
    {
        $users = User::orderBy('role')
            ->orderBy('nama')
            ->get();

        return view('user.index', compact('users'));
    }

    // Form tambah akun staf baru
    public function create()
    {
        return view('user.create');
    }

    // Simpan akun staf baru
    // Role dipilih dari form: ADMIN / OWNER / KURIR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|min:3|max:100',
            'email'      => 'required|email|max:120|unique:users,email',
            'password'   => 'required|min:6',
            'no_telepon' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s]{10,20}$/'
            ],
            'role'       => 'required|in:ADMIN,OWNER,KURIR',
        ]);

        User::create([
            'nama'        => $validated['nama'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'no_telepon'  => $validated['no_telepon'],
            'role'        => $validated['role'],
            'status_akun' => 'AKTIF',
        ]);

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'Akun staf berhasil ditambahkan'
            );
    }

    // Nonaktifkan akun staf
    public function nonaktifkan($id)
    {
        $user = User::findOrFail($id);

        // Owner tidak boleh menonaktifkan akunnya sendiri
        // agar tidak terkunci dari sistem.
        if (
            (int) $user->id_user ===
            (int) Auth::guard('web')->id()
        ) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat menonaktifkan akun Anda sendiri'
            ]);
        }

        $user->update([
            'status_akun' => 'NONAKTIF'
        ]);

        return back()->with(
            'success',
            'Akun staf berhasil dinonaktifkan'
        );
    }

    // Aktifkan kembali akun staf
    public function aktifkan($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status_akun' => 'AKTIF'
        ]);

        return back()->with(
            'success',
            'Akun staf berhasil diaktifkan'
        );
    }
}