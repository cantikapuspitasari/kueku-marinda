<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminKategoriController extends Controller
{
    // Nama kolom "nama kategori" di tabel kategori
    private function kolomNama(): string
    {
        $kolom = Schema::getColumnListing('kategori');
        return collect(['nama_kategori', 'nama', 'nama_kat'])->first(fn ($c) => in_array($c, $kolom)) ?? 'nama_kategori';
    }

    public function index()
    {
        $kolom = $this->kolomNama();

        $kategori = DB::table('kategori')
            ->select('kategori.*', DB::raw('(select count(*) from produk where produk.id_kategori = kategori.id_kategori) as jumlah_produk'))
            ->orderBy($kolom)
            ->get()
            ->map(function ($k) use ($kolom) {
                $k->nama_tampil = $k->{$kolom};
                return $k;
            });

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $kolom = $this->kolomNama();
        $data = $request->validate(['nama' => 'required|string|max:100']);

        if (DB::table('kategori')->where($kolom, $data['nama'])->exists()) {
            return back()->withInput()->withErrors(['nama' => 'Kategori "' . $data['nama'] . '" sudah ada.']);
        }

        try {
            DB::table('kategori')->insert([$kolom => $data['nama']]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['nama' => 'Kategori gagal disimpan: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Kategori "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kolom = $this->kolomNama();
        $data = $request->validate(['nama' => 'required|string|max:100']);

        $dobel = DB::table('kategori')->where($kolom, $data['nama'])->where('id_kategori', '!=', $id)->exists();
        if ($dobel) {
            return back()->withErrors(['nama' => 'Kategori "' . $data['nama'] . '" sudah ada.']);
        }

        DB::table('kategori')->where('id_kategori', $id)->update([$kolom => $data['nama']]);

        return back()->with('success', 'Kategori berhasil diperbarui menjadi "' . $data['nama'] . '".');
    }

    public function destroy($id)
    {
        $kolom = $this->kolomNama();
        $kategori = DB::table('kategori')->where('id_kategori', $id)->first();
        abort_if(!$kategori, 404);

        if (DB::table('produk')->where('id_kategori', $id)->exists()) {
            return back()->withErrors(['nama' => 'Kategori "' . $kategori->{$kolom} . '" masih dipakai produk, tidak bisa dihapus. Pindahkan produknya dulu.']);
        }

        DB::table('kategori')->where('id_kategori', $id)->delete();

        return back()->with('success', 'Kategori "' . $kategori->{$kolom} . '" berhasil dihapus.');
    }
}
