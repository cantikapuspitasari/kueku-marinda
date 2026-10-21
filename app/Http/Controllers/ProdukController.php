<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Data katalog untuk JavaScript (keranjang, checkout, detail pesanan).
    // Kunci = id_produk, jadi semua halaman memakai data yang sama dari database.
    public static function katalogJs(): array
    {
        return Produk::with('kategori')->get()->mapWithKeys(function ($p) {
            $kat = $p->kategori->nama_kategori ?? $p->kategori->nama ?? 'Produk';

            return [$p->id_produk => [
                'name'  => $p->nama_produk,
                'price' => (int) $p->harga,
                'sub'   => $kat . ' • Fresh Baked',
                'img'   => $p->foto_produk ? asset('images/produk/' . $p->foto_produk) : '',
                'stok'  => (int) $p->stok,
                'aktif' => $p->status_produk === 'TERSEDIA',
            ]];
        })->all();
    }

    // Menampilkan semua produk (beranda pelanggan)
    public function index()
    {
        $produk = Produk::with('kategori')
            ->where('status_produk', '!=', 'NONAKTIF')
            ->orderBy('id_kategori')
            ->orderBy('id_produk')
            ->get();

        return view('produk.index', compact('produk'));
    }

    // Form tambah produk baru (Admin)
    public function create()
    {
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    // Simpan produk baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_produk'   => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'status_produk' => 'required|in:TERSEDIA,STOK_HABIS,NONAKTIF',
            'foto_produk'   => 'nullable|string|max:255',
        ]);

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // Detail 1 produk
    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_produk'   => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'status_produk' => 'required|in:TERSEDIA,STOK_HABIS,NONAKTIF',
            'foto_produk'   => 'nullable|string|max:255',
        ]);

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
