<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerAkunController extends Controller
{
    // Owner hanya mengelola akun staf: ADMIN dan KURIR
    private const PERAN = ['ADMIN', 'KURIR'];

    public function index()
    {
        $akun = DB::table('users')
            ->whereIn('role', self::PERAN)
            ->orderByRaw("FIELD(role, 'ADMIN', 'KURIR')")
            ->orderBy('nama')
            ->get();

        return view('owner.akun', compact('akun'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'email'      => 'required|email|max:120|unique:users,email',
            'no_telepon' => 'nullable|string|max:20',
            'password'   => 'required|string|min:8|max:100',
            'role'       => 'required|in:ADMIN,KURIR',
        ], [
            'email.unique'   => 'Email ini sudah dipakai akun lain.',
            'password.min'   => 'Kata sandi minimal 8 karakter.',
            'role.in'        => 'Peran harus Admin atau Kurir.',
        ]);

        DB::table('users')->insert([
            'nama'        => $data['nama'],
            'email'       => $data['email'],
            'no_telepon'  => $data['no_telepon'] ?? null,
            'password'    => Hash::make($data['password']),
            'role'        => $data['role'],
            'status_akun' => 'AKTIF',
        ]);

        return redirect()->route('owner.akun')->with('success', 'Akun ' . $data['nama'] . ' berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $akun = $this->cariStaf($id);

        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'role'       => 'required|in:ADMIN,KURIR',
            'password'   => 'nullable|string|min:8|max:100',
        ]);

        $ubah = [
            'nama'       => $data['nama'],
            'no_telepon' => $data['no_telepon'] ?? null,
            'role'       => $data['role'],
        ];
        if (!empty($data['password'])) {
            $ubah['password'] = Hash::make($data['password']);
        }

        DB::table('users')->where('id_user', $akun->id_user)->update($ubah);

        return redirect()->route('owner.akun')->with('success', 'Akun ' . $data['nama'] . ' berhasil diperbarui.');
    }

    public function nonaktifkan($id)
    {
        $akun = $this->cariStaf($id);
        DB::table('users')->where('id_user', $akun->id_user)->update(['status_akun' => 'NONAKTIF']);

        return back()->with('success', 'Akun ' . $akun->nama . ' dinonaktifkan. Sesi login-nya langsung tidak berlaku.');
    }

    public function aktifkan($id)
    {
        $akun = $this->cariStaf($id);
        DB::table('users')->where('id_user', $akun->id_user)->update(['status_akun' => 'AKTIF']);

        return back()->with('success', 'Akun ' . $akun->nama . ' diaktifkan kembali.');
    }

    // Hanya akun ADMIN/KURIR yang bisa disentuh; Owner dan pembeli tidak bisa lewat sini
    private function cariStaf($id)
    {
        $akun = DB::table('users')->where('id_user', $id)->whereIn('role', self::PERAN)->first();
        abort_if(!$akun, 404);

        return $akun;
    }
}
