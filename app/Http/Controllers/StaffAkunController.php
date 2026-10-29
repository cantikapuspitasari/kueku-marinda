<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// "Akun Saya" untuk Admin, Owner, dan Kurir (halaman yang sama, peran berbeda)
class StaffAkunController extends Controller
{
    private function kembali($role): string
    {
        return match ($role) {
            'OWNER' => route('owner.dashboard'),
            'KURIR' => route('kurir.dashboard'),
            default => route('admin.dashboard'),
        };
    }

    public function index()
    {
        $user = Auth::user();
        $peran = ['ADMIN' => 'Administrator', 'OWNER' => 'Owner', 'KURIR' => 'Kurir'][$user->role] ?? $user->role;

        return view('staff.akun', ['user' => $user, 'peran' => $peran, 'kembali' => $this->kembali($user->role)]);
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email'      => 'required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',
        ], ['email.unique' => 'Email sudah dipakai akun lain.']);

        DB::table('users')->where('id_user', $user->id_user)->update([
            'nama'       => $data['nama'],
            'no_telepon' => $data['no_telepon'] ?? '',
            'email'      => $data['email'],
        ]);

        return back()->with('success', 'Informasi akun berhasil disimpan.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_baru.min'       => 'Kata sandi baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi kata sandi baru tidak sama.',
        ]);

        $user = Auth::user();
        if (!Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi lama salah.']);
        }

        DB::table('users')->where('id_user', $user->id_user)
            ->update(['password' => Hash::make($data['password_baru'])]);

        return back()->with('success', 'Kata sandi berhasil diganti.');
    }
}
