<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Cari nama kolom nama-kategori di tabel "kategori"
        $kolom = Schema::getColumnListing('kategori');
        $kolomNama = collect(['nama_kategori', 'nama', 'nama_kat'])->first(fn ($c) => in_array($c, $kolom));
        if (!$kolomNama) {
            throw new \RuntimeException('Kolom nama pada tabel kategori tidak ditemukan. Kolom yang ada: ' . implode(', ', $kolom));
        }

        // [nama, kategori, harga, stok, nama file foto, deskripsi]
        $produk = [
            ['Baked Cheesecake Strawberry 15cm', 'Cake Medium 15cm', 200000, 10, 'baked-cheesecake-strawberry-15cm.webp', 'Cheesecake panggang lembut dengan topping strawberry segar. Diameter 15cm.'],
            ['Coffee Cake 15cm',                 'Cake Medium 15cm', 180000, 10, 'coffee-cake-15cm.webp',                 'Cake kopi lembut dengan cream halus. Gratis tulisan dan 4 pcs lilin.'],
            ['Nobake Cheesecake Strawberry 15cm','Cake Medium 15cm', 180000, 10, 'nobake-cheesecake-strawberry-15cm.webp','Cheesecake tanpa panggang dengan rasa segar dan topping strawberry. Diameter 15cm.'],
            ['Bento Mix Fruit Cheesecake',       'Bento Cake 10cm',   74000, 15, 'bento-mix-fruit-cheesecake.webp',       'Cheesecake creamy asam segar dengan topping aneka buah. Diameter 10cm.'],
            ['Bento Red Velvet Cream Cheese',    'Bento Cake 10cm',   74000, 15, 'bento-red-velvet.webp',                 'Cake red velvet dengan cream cheese frosting. Diameter 10cm.'],
            ['Bento Strawberry Cheesecake',      'Bento Cake 10cm',   67000, 15, 'bento-strawberry-cheesecake.webp',      'Cheesecake asam segar dengan saus strawberry. Diameter 10cm.'],
            ['Milkbun Vanilla isi 9',            'Roti',              34000, 20, 'milkbun-vanilla.webp',                  'Roti lembut dipadukan cream vanilla, isi 9 buah.'],
            ['Roti Abon isi 2',                  'Roti',              18000, 25, 'roti-abon.webp',                        'Roti lembut dengan isian mayo dan abon gurih, isi 2 buah.'],
            ['Roti Choco Cheese',                'Roti',              18000, 25, 'roti-choco-cheese.webp',                'Roti lembut dengan isian cokelat dan taburan keju.'],
            ['Roti Cinnamoroll',                 'Roti',              18000, 25, 'roti-cinnamoroll.webp',                 'Roti gulung kayu manis dengan glaze manis.'],
            ['Klappertaart Panggang',            'Pastry & Dessert',  27000, 20, 'klappertaart-panggang.webp',            'Cream vanilla dan potongan kelapa muda yang dipanggang.'],
            ['Sus Krim Buah isi 4',              'Pastry & Dessert',  34000, 20, 'sus-krim-buah.webp',                    'Sus lembut dengan isian cream vanilla dan buah segar, isi 4 buah.'],
            ['Cendol Nangka Gula Aren',          'Minuman',           24000, 30, 'cendol-nangka-gula-aren.webp',          'Cendol kekinian dengan nangka dan gula aren.'],
            ['Kopi Susu Gula Aren',              'Minuman',           20000, 30, 'kopi-susu-gula-aren.webp',              'Kopi susu creamy dengan gula aren.'],
        ];

        $namaProduk = [];

        foreach ($produk as [$nama, $kategori, $harga, $stok, $foto, $deskripsi]) {
            $idKategori = DB::table('kategori')->where($kolomNama, $kategori)->value('id_kategori')
                ?? DB::table('kategori')->insertGetId([$kolomNama => $kategori]);

            $sudahAda = DB::table('produk')->where('nama_produk', $nama)->exists();

            $data = [
                'id_kategori'   => $idKategori,
                'deskripsi'     => $deskripsi,
                'harga'         => $harga,
                'foto_produk'   => $foto,
                'status_produk' => 'TERSEDIA',
            ];
            // Stok hanya diisi saat produk pertama kali dibuat (supaya stok yang sudah berkurang tidak ter-reset)
            if (!$sudahAda) {
                $data['stok'] = $stok;
            }

            DB::table('produk')->updateOrInsert(['nama_produk' => $nama], $data);
            $namaProduk[] = $nama;
        }

        // Produk contoh lama (bukan bagian katalog sekarang) dinonaktifkan, tidak dihapus
        DB::table('produk')->whereNotIn('nama_produk', $namaProduk)->update(['status_produk' => 'NONAKTIF']);
    }
}
