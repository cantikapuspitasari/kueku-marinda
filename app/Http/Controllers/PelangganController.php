<?php

namespace App\Http\Controllers;

use App\Support\TahapPesanan;
use App\Support\Telepon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Halaman khusus pelanggan: Pesanan Saya & Profil
class PelangganController extends Controller
{
    // Label status untuk pelanggan
    private const LABEL_KURIR = ['Pesanan Terkonfirmasi', 'Sedang Dikemas', 'Diserahkan ke Kurir', 'Sedang Dalam Perjalanan', 'Pesanan Sampai'];
    private const LABEL_TOKO  = ['Pesanan Terkonfirmasi', 'Sedang Dikemas', 'Siap Diambil', 'Menuju Toko', 'Pesanan Diterima'];

    private function idPembeli($user)
    {
        return DB::table('pembeli')->where('email', $user->email)->value('id_pembeli');
    }

    // ---------------- Pesanan Saya ----------------
    public function pesanan()
    {
        $user = Auth::user();
        $idPembeli = $this->idPembeli($user);

        $pesanan = $idPembeli
            ? DB::table('pesanan')->where('id_pembeli', $idPembeli)
                ->orderByDesc('tanggal_pesan')->orderByDesc('id_pesanan')->get()
            : collect();

        $ids = $pesanan->pluck('id_pesanan');

        $item = DB::table('detail_pesanan as d')
            ->join('produk as p', 'p.id_produk', '=', 'd.id_produk')
            ->whereIn('d.id_pesanan', $ids)
            ->get(['d.id_pesanan', 'd.jumlah', 'p.nama_produk', 'p.foto_produk'])
            ->groupBy('id_pesanan');

        $kirim = DB::table('pengiriman')->whereIn('id_pesanan', $ids)->get()->keyBy('id_pesanan');

        $daftar = $pesanan->map(function ($p) use ($item, $kirim) {
            $h = TahapPesanan::hitung($p->status_pesanan, $p->jenis_pengambilan, $kirim[$p->id_pesanan]->status_pengiriman ?? null);
            $label = match ($h['state']) {
                'batal'    => 'Dibatalkan',
                'menunggu' => 'Menunggu Verifikasi',
                default    => ($p->jenis_pengambilan === 'PICKUP' ? self::LABEL_TOKO : self::LABEL_KURIR)[$h['tahap']],
            };
            return (object) [
                'kode'   => $p->kode_pesanan,
                'tanggal'=> $p->tanggal_pesan,
                'jenis'  => $p->jenis_pengambilan,
                'total'  => $p->total_harga,
                'state'  => $h['state'],
                'tahap'  => $h['tahap'],
                'label'  => $label,
                'item'   => $item[$p->id_pesanan] ?? collect(),
            ];
        });

        return view('pelanggan.pesanan', ['daftar' => $daftar]);
    }

    // ---------------- Profil Saya ----------------
    public function profil()
    {
        $user = Auth::user();
        $idPembeli = $this->idPembeli($user);

        $hitung = fn (array $status) => $idPembeli
            ? DB::table('pesanan')->where('id_pembeli', $idPembeli)->whereIn('status_pesanan', $status)->count()
            : 0;

        $stat = [
            'selesai' => $hitung(['SELESAI']),
            'proses'  => $hitung(['PENDING', 'DIKONFIRMASI', 'DIPROSES']),
        ];

        // Nomor WhatsApp admin diambil dari akun ADMIN aktif (bisa diganti lewat Kelola Akun)
        $waAdmin = DB::table('users')->where('role', 'ADMIN')->where('status_akun', 'AKTIF')
            ->whereNotNull('no_telepon')->where('no_telepon', '!=', '')->value('no_telepon');
        $waAdmin = $waAdmin ? preg_replace('/^0/', '62', preg_replace('/\D/', '', $waAdmin)) : null;

        return view('pelanggan.profil', compact('user', 'stat', 'waAdmin'));
    }

    // ---------------- Edit Informasi Pribadi ----------------
    public function info()
    {
        return view('pelanggan.profil-info', ['user' => Auth::user()]);
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'no_telepon' => 'required|string|min:9|max:20',
            'email'      => 'required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',
        ], [
            'email.unique' => 'Email sudah dipakai akun lain.',
            'no_telepon.required' => 'No. HP wajib diisi.',
            'no_telepon.min' => 'No. HP minimal 9 digit.',
        ]);

        $telp = Telepon::normalisasi($data['no_telepon']);
        $emailLama = $user->email;

        DB::transaction(function () use ($user, $data, $telp, $emailLama) {
            DB::table('users')->where('id_user', $user->id_user)->update([
                'nama' => $data['nama'], 'no_telepon' => $telp, 'email' => $data['email'],
            ]);
            // Tabel pembeli dicocokkan lewat email, jadi ikut diperbarui
            DB::table('pembeli')->where('email', $emailLama)->update([
                'nama' => $data['nama'], 'no_telepon' => $telp, 'email' => $data['email'],
            ]);
        });

        return redirect()->route('profil')->with('success', 'Informasi pribadi berhasil disimpan.');
    }

    // ---------------- Edit Alamat ----------------
    public function alamat()
    {
        $user = Auth::user();
        $idPembeli = $this->idPembeli($user);

        $alamat = $idPembeli
            ? DB::table('alamat')->where('id_pembeli', $idPembeli)->orderByDesc('id_alamat')->get()
            : collect();

        return view('pelanggan.profil-alamat', compact('user', 'alamat'));
    }

    public function tambahAlamat(Request $request)
    {
        $data = $request->validate([
            'label_alamat'   => 'nullable|string|max:50',
            'alamat_lengkap' => 'required|string|max:500',
        ], ['alamat_lengkap.required' => 'Alamat lengkap wajib diisi.']);

        $user = Auth::user();
        $idPembeli = $this->idPembeli($user)
            ?: DB::table('pembeli')->insertGetId([
                'nama' => $user->nama, 'email' => $user->email,
                'password' => $user->password, 'no_telepon' => $user->no_telepon ?? '',
            ]);

        DB::table('alamat')->insert([
            'id_pembeli'     => $idPembeli,
            'label_alamat'   => $data['label_alamat'] ?: 'Rumah',
            'alamat_lengkap' => $data['alamat_lengkap'],
            'kota'           => '-',
            'kode_pos'       => '-',
        ]);

        return redirect()->route('profil.alamat')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function ubahAlamat(Request $request, $id)
    {
        $data = $request->validate([
            'label_alamat'   => 'nullable|string|max:50',
            'alamat_lengkap' => 'required|string|max:500',
        ], ['alamat_lengkap.required' => 'Alamat lengkap wajib diisi.']);

        $this->alamatMilik($id);

        DB::table('alamat')->where('id_alamat', $id)->update([
            'label_alamat'   => $data['label_alamat'] ?: 'Rumah',
            'alamat_lengkap' => $data['alamat_lengkap'],
        ]);

        return redirect()->route('profil.alamat')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function hapusAlamat($id)
    {
        $this->alamatMilik($id);

        // Alamat yang sudah dipakai pesanan tidak boleh dihapus (menjaga riwayat pesanan)
        if (DB::table('pesanan')->where('id_alamat', $id)->exists()) {
            return back()->withErrors(['alamat' => 'Alamat ini sudah dipakai pada pesanan sehingga tidak bisa dihapus. Anda masih bisa mengeditnya.']);
        }

        DB::table('alamat')->where('id_alamat', $id)->delete();
        return redirect()->route('profil.alamat')->with('success', 'Alamat dihapus.');
    }

    // Pastikan alamat milik pelanggan yang sedang login
    private function alamatMilik($id)
    {
        $idPembeli = $this->idPembeli(Auth::user());
        $ada = DB::table('alamat')->where('id_alamat', $id)->where('id_pembeli', $idPembeli)->exists();
        abort_if(!$ada, 404);
    }
}
