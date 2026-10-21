<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminProdukController extends Controller
{
    // Daftar kategori untuk dropdown: [{id, nama}]
    private function daftarKategori()
    {
        $kolom = Schema::getColumnListing('kategori');
        $nama = collect(['nama_kategori', 'nama', 'nama_kat'])->first(fn ($c) => in_array($c, $kolom)) ?? 'nama_kategori';

        return DB::table('kategori')->get()->map(fn ($k) => (object) [
            'id'   => $k->id_kategori,
            'nama' => $k->{$nama},
        ]);
    }

    private function aturan(): array
    {
        return [
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_produk'   => 'required|string|max:100',
            'deskripsi'     => 'nullable|string|max:1000',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'status_produk' => 'required|in:TERSEDIA,STOK_HABIS,NONAKTIF',
            'foto'          => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    // Status otomatis mengikuti stok, kecuali admin menonaktifkan produk
    private function statusDariStok(string $status, int $stok): string
    {
        if ($status === 'NONAKTIF') {
            return 'NONAKTIF';
        }
        return $stok > 0 ? 'TERSEDIA' : 'STOK_HABIS';
    }

    private function simpanFoto(Request $request, string $nama): ?string
    {
        if (!$request->hasFile('foto')) {
            return null;
        }
        $file = $request->file('foto');
        $namaFile = Str::slug($nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/produk'), $namaFile);

        return $namaFile;
    }

    private function hapusFile(?string $namaFile): void
    {
        if ($namaFile && File::exists(public_path('images/produk/' . $namaFile))) {
            File::delete(public_path('images/produk/' . $namaFile));
        }
    }

    public function index(Request $request)
    {
        $produk = Produk::with('kategori')
            ->orderBy('id_kategori')
            ->orderBy('nama_produk')
            ->get();

        $kategori = $this->daftarKategori();

        return view('admin.produk.index', compact('produk', 'kategori'));
    }

    public function create()
    {
        return view('admin.produk.form', ['produk' => null, 'kategori' => $this->daftarKategori()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturan());

        DB::table('produk')->insert([
            'id_kategori'   => $data['id_kategori'],
            'nama_produk'   => $data['nama_produk'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'harga'         => $data['harga'],
            'stok'          => $data['stok'],
            'status_produk' => $this->statusDariStok($data['status_produk'], (int) $data['stok']),
            'foto_produk'   => $this->simpanFoto($request, $data['nama_produk']),
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk "' . $data['nama_produk'] . '" berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.form', ['produk' => $produk, 'kategori' => $this->daftarKategori()]);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->validate($this->aturan());

        $ubah = [
            'id_kategori'   => $data['id_kategori'],
            'nama_produk'   => $data['nama_produk'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'harga'         => $data['harga'],
            'stok'          => $data['stok'],
            'status_produk' => $this->statusDariStok($data['status_produk'], (int) $data['stok']),
        ];

        $fotoBaru = $this->simpanFoto($request, $data['nama_produk']);
        if ($fotoBaru) {
            $this->hapusFile($produk->foto_produk);
            $ubah['foto_produk'] = $fotoBaru;
        }

        DB::table('produk')->where('id_produk', $id)->update($ubah);

        return redirect()->route('admin.produk.index')->with('success', 'Produk "' . $data['nama_produk'] . '" berhasil diperbarui.');
    }

    // Ubah stok cepat dari tabel
    public function updateStok(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->validate(['stok' => 'required|integer|min:0|max:100000']);

        DB::table('produk')->where('id_produk', $id)->update([
            'stok'          => $data['stok'],
            'status_produk' => $this->statusDariStok($produk->status_produk, (int) $data['stok']),
        ]);

        return back()->with('success', 'Stok "' . $produk->nama_produk . '" diperbarui menjadi ' . $data['stok'] . '.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Produk yang sudah pernah dipesan tidak boleh dihapus (riwayat pesanan tetap utuh), cukup dinonaktifkan
        if (DB::table('detail_pesanan')->where('id_produk', $id)->exists()) {
            DB::table('produk')->where('id_produk', $id)->update(['status_produk' => 'NONAKTIF']);
            return back()->with('success', 'Produk "' . $produk->nama_produk . '" sudah pernah dipesan, jadi dinonaktifkan (tidak dihapus).');
        }

        $this->hapusFile($produk->foto_produk);
        DB::table('produk')->where('id_produk', $id)->delete();

        return back()->with('success', 'Produk "' . $produk->nama_produk . '" berhasil dihapus.');
    }
}
