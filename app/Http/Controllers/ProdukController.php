<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan semua produk (untuk katalog pembeli / list admin)
    public function index()
    {
        $produk = Produk::with('kategori')->get();

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
        ]);

        // Jika stok 0, produk tidak boleh berstatus TERSEDIA
        if (
            $validated['status_produk'] === 'TERSEDIA' &&
            (int) $validated['stok'] === 0
        ) {
            $validated['status_produk'] = 'STOK_HABIS';
        }

        Produk::create($validated);

        return redirect()
            ->route('produk.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan'
            );
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
        ]);

        // Jika stok 0, produk tidak boleh berstatus TERSEDIA
        if (
            $validated['status_produk'] === 'TERSEDIA' &&
            (int) $validated['stok'] === 0
        ) {
            $validated['status_produk'] = 'STOK_HABIS';
        }

        $produk->update($validated);

        return redirect()
            ->route('produk.index')
            ->with(
                'success',
                'Produk berhasil diperbarui'
            );
    }

    // Hapus produk
    // Produk yang pernah dipesan tidak benar-benar dihapus
    // supaya riwayat pesanan dan laporan tetap aman.
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Jika produk pernah masuk pesanan,
        // cukup dinonaktifkan.
        if ($produk->detailPesanan()->exists()) {

            $produk->update([
                'status_produk' => 'NONAKTIF'
            ]);

            return redirect()
                ->route('produk.index')
                ->with(
                    'success',
                    'Produk sudah pernah dipesan, jadi dinonaktifkan (tidak dihapus).'
                );
        }

        // Jika belum pernah dipesan,
        // produk boleh dihapus.
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with(
                'success',
                'Produk berhasil dihapus'
            );
    }
}