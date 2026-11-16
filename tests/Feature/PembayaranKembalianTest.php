<?php

namespace Tests\Feature;

use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Menguji pembayaran tunai + kembalian (pickup oleh admin, delivery oleh kurir),
 * struk, dan riwayat pesanan pembeli.
 *
 * Butuh database MySQL yang berisi tabel dari database/schema_kuekumarinda.sql
 * plus migration jam_ambil (pesanan) dan uang_diterima/kembalian (pembayaran). Setiap tes dibungkus transaksi lalu di-rollback,
 * jadi tidak ada data yang tersisa. Dilewati otomatis bila lingkungannya tidak sesuai.
 *
 * Contoh menjalankan:
 *   DB_CONNECTION=mysql DB_DATABASE=kuekumarinda_test php artisan test --filter=PembayaranKembalianTest
 */
class PembayaranKembalianTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;
    private User $kurir;
    private User $kurirLain;
    private User $owner;
    private Pembeli $pembeli;
    private Pembeli $pembeliLain;

    protected function setUp(): void
    {
        parent::setUp();

        if (
            config('database.default') !== 'mysql'
            || ! Schema::hasTable('pembayaran')
            || ! Schema::hasColumn('pembayaran', 'uang_diterima')
        ) {
            $this->markTestSkipped('Butuh MySQL dengan skema project + migration uang_diterima/kembalian.');
        }

        $buatStaf = fn (string $role) => User::create([
            'nama' => "Uji $role", 'email' => strtolower($role) . uniqid() . '@uji.test',
            'password' => 'x', 'no_telepon' => '081', 'role' => $role, 'status_akun' => 'AKTIF',
        ]);
        $this->admin     = $buatStaf('ADMIN');
        $this->owner     = $buatStaf('OWNER');
        $this->kurir     = $buatStaf('KURIR');
        $this->kurirLain = $buatStaf('KURIR');

        $buatPembeli = fn (string $nama) => Pembeli::create([
            'nama' => $nama, 'email' => strtolower($nama) . uniqid() . '@uji.test',
            'password' => 'x', 'no_telepon' => '081', 'status_akun' => 'AKTIF',
        ]);
        $this->pembeli     = $buatPembeli('Pembeli Satu');
        $this->pembeliLain = $buatPembeli('Pembeli Dua');
    }

    // ---------- helper data ----------

    private function buatPesanan(string $jenis, string $status = 'DIPROSES', int $total = 75000, ?Pembeli $pembeli = null): int
    {
        $pembeli ??= $this->pembeli;

        $idAlamat = DB::table('alamat')->insertGetId([
            'id_pembeli' => $pembeli->id_pembeli, 'label_alamat' => 'Rumah',
            'alamat_lengkap' => 'Jl. Uji 1', 'kota' => 'Bandung', 'kode_pos' => '40000',
        ]);

        $id = DB::table('pesanan')->insertGetId([
            'kode_pesanan' => 'UJI-' . uniqid(), 'id_pembeli' => $pembeli->id_pembeli,
            'id_alamat' => $jenis === 'DELIVERY' ? $idAlamat : null,
            'tanggal_pesan' => now()->toDateTimeString(), 'tanggal_ambil' => now()->toDateString(),
            'jam_ambil' => '10:00:00', 'jenis_pengambilan' => $jenis,
            'status_pesanan' => $status, 'total_harga' => $total,
        ]);

        DB::table('detail_pesanan')->insert([
            'id_pesanan' => $id, 'id_produk' => 1, 'jumlah' => 2,
            'subtotal' => $jenis === 'DELIVERY' ? $total - 5000 : $total,
        ]);

        return $id;
    }

    private function tugaskan(int $idPesanan, User $kurir, string $status = 'DIANTAR'): void
    {
        DB::table('pengiriman')->insert([
            'id_pesanan' => $idPesanan, 'id_user' => $kurir->id_user, 'status_pengiriman' => $status,
        ]);
    }

    private function bayar(User $staf, int $idPesanan, array $data)
    {
        return $this->actingAs($staf, 'web')
            ->from('/halaman-asal')
            ->post(route('pembayaran.store', $idPesanan), $data);
    }

    private function jumlahPembayaran(int $idPesanan): int
    {
        return DB::table('pembayaran')->where('id_pesanan', $idPesanan)->count();
    }

    // ---------- PICKUP (admin) ----------

    public function test_pickup_uang_lebih_besar_menghasilkan_kembalian(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $res = $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000]);

        $res->assertRedirect('/halaman-asal')->assertSessionHas('success');
        $this->assertStringContainsString('Rp25.000', session('success'));

        $row = DB::table('pembayaran')->where('id_pesanan', $id)->first();
        $this->assertEquals(75000, $row->jumlah_bayar);
        $this->assertEquals(100000, $row->uang_diterima);
        $this->assertEquals(25000, $row->kembalian);
        $this->assertEquals($this->admin->id_user, $row->id_user);
        $this->assertEquals('SELESAI', DB::table('pesanan')->where('id_pesanan', $id)->value('status_pesanan'));
    }

    public function test_pickup_uang_pas_kembalian_nol(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 75000])
            ->assertSessionHas('success');

        $this->assertEquals(0, DB::table('pembayaran')->where('id_pesanan', $id)->value('kembalian'));
    }

    public function test_pickup_uang_kurang_ditolak_dan_tidak_disimpan(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 50000])
            ->assertSessionHasErrors('pesanan');

        $this->assertStringContainsString('kurang Rp25.000', session('errors')->first('pesanan'));
        $this->assertSame(0, $this->jumlahPembayaran($id));
        $this->assertEquals('DIPROSES', DB::table('pesanan')->where('id_pesanan', $id)->value('status_pesanan'));
    }

    public function test_kembalian_dari_browser_diabaikan_dan_dihitung_ulang_di_server(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->admin, $id, [
            'tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000,
            'kembalian' => 999999, 'jumlah_bayar' => 1,
        ]);

        $row = DB::table('pembayaran')->where('id_pesanan', $id)->first();
        $this->assertEquals(25000, $row->kembalian);
        $this->assertEquals(75000, $row->jumlah_bayar);
    }

    #[DataProvider('nominalTidakValid')]
    public function test_nominal_tidak_valid_ditolak_validasi($nilai): void
    {
        $id = $this->buatPesanan('PICKUP');

        $data = ['tempat_pembayaran' => 'TOKO'];
        if ($nilai !== null) {
            $data['uang_diterima'] = $nilai;
        }

        $this->bayar($this->admin, $id, $data)->assertSessionHasErrors('uang_diterima');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public static function nominalTidakValid(): array
    {
        return [
            'kosong'       => [null],
            'negatif'      => [-100],
            'bukan angka'  => ['abc'],
            'desimal'      => ['75000.50'],
            'terlalu besar'=> [1000000000],
        ];
    }

    public function test_pembayaran_tidak_bisa_dicatat_dua_kali(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000]);
        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 200000])
            ->assertSessionHasErrors('pesanan');

        $this->assertSame(1, $this->jumlahPembayaran($id));
        $this->assertEquals(100000, DB::table('pembayaran')->where('id_pesanan', $id)->value('uang_diterima'));
    }

    public function test_pesanan_pending_tidak_bisa_dibayar(): void
    {
        $id = $this->buatPesanan('PICKUP', 'PENDING');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000])
            ->assertSessionHasErrors('pesanan');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    // ---------- DELIVERY (kurir) & hak akses ----------

    public function test_kurir_yang_ditugaskan_bisa_input_pembayaran_delivery(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->bayar($this->kurir, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 100000])
            ->assertSessionHas('success');

        $row = DB::table('pembayaran')->where('id_pesanan', $id)->first();
        $this->assertEquals(25000, $row->kembalian);
        $this->assertEquals($this->kurir->id_user, $row->id_user);
        // Delivery: status pesanan diurus alur pengiriman, bukan otomatis SELESAI
        $this->assertEquals('DIPROSES', DB::table('pesanan')->where('id_pesanan', $id)->value('status_pesanan'));
    }

    public function test_kurir_lain_tidak_bisa_input_pembayaran_delivery(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->bayar($this->kurirLain, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 100000])
            ->assertSessionHasErrors('pesanan');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public function test_admin_tidak_bisa_input_pembayaran_delivery(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 100000])
            ->assertSessionHasErrors('pesanan');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public function test_kurir_tidak_bisa_input_pembayaran_pickup(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->kurir, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000])
            ->assertSessionHasErrors('pesanan');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public function test_tempat_pembayaran_harus_sesuai_jenis_pesanan(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 100000])
            ->assertSessionHasErrors('pesanan');
        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public function test_owner_dan_pembeli_tidak_boleh_menyimpan_pembayaran(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->bayar($this->owner, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000])->assertForbidden();

        // Pembeli ditolak: dialihkan ke login staf (302) atau ditolak CheckRole (403), tergantung guard aktif.
        $status = $this->actingAs($this->pembeli, 'pembeli')
            ->post(route('pembayaran.store', $id), ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000])
            ->getStatusCode();
        $this->assertContains($status, [302, 403]);

        $this->assertSame(0, $this->jumlahPembayaran($id));
    }

    public function test_kurir_wajib_catat_pembayaran_sebelum_menandai_terkirim(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->actingAs($this->kurir, 'web')->from('/x')
            ->patch(route('pengiriman.tandaiTerkirim', $id))
            ->assertSessionHasErrors('pengiriman');
        $this->assertEquals('DIPROSES', DB::table('pesanan')->where('id_pesanan', $id)->value('status_pesanan'));

        $this->bayar($this->kurir, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 80000]);

        $this->actingAs($this->kurir, 'web')->from('/x')
            ->patch(route('pengiriman.tandaiTerkirim', $id))
            ->assertSessionHas('success');
        $this->assertEquals('SELESAI', DB::table('pesanan')->where('id_pesanan', $id)->value('status_pesanan'));
    }

    // ---------- STRUK ----------

    public function test_admin_melihat_input_uang_diterima_di_struk_pickup_belum_bayar(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $res = $this->actingAs($this->admin, 'web')->get(route('pesanan.struk', $id));

        $res->assertOk()
            ->assertSee('data-bayar-input', false)
            ->assertSee('BELUM DIBAYAR')
            ->assertSee('Cetak Struk')
            ->assertSee('disabled title="Simpan pembayaran dulu', false);
    }

    public function test_struk_menampilkan_uang_diterima_dan_kembalian_setelah_dibayar(): void
    {
        $id = $this->buatPesanan('PICKUP');
        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000]);

        $res = $this->actingAs($this->admin, 'web')->get(route('pesanan.struk', $id));

        $res->assertOk()
            ->assertDontSee('data-bayar-input', false)
            ->assertDontSee('BELUM DIBAYAR')
            ->assertSee('Pembeli Satu')
            ->assertSee('Uang diterima :', false)
            ->assertSee('Rp100.000')
            ->assertSee('Kembalian :', false)
            ->assertSee('Rp25.000')
            ->assertSee('Grand Total')
            ->assertSee('Rp75.000')
            ->assertSee('Tgl Pesan')
            ->assertDontSee('disabled title="Simpan pembayaran dulu', false);
    }

    public function test_tombol_kembali_struk_mengarah_ke_halaman_sesuai_peran(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $cek = fn ($akun, string $guard, string $tujuan) => $this->actingAs($akun, $guard)
            ->get(route('pesanan.struk', $id))
            ->assertOk()
            ->assertSee('<a href="' . $tujuan . '">Kembali</a>', false)
            ->assertDontSee('history.back', false);

        $cek($this->admin, 'web', route('admin.pesanan'));
        $cek($this->kurir, 'web', route('kurir.pesanan'));
        $cek($this->owner, 'web', route('owner.monitoring'));
    }

    public function test_tombol_kembali_struk_pembeli_mengarah_ke_riwayat_pesanan(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->actingAs($this->pembeli, 'pembeli')
            ->get(route('pesanan.struk', $id))
            ->assertOk()
            ->assertSee('<a href="' . route('pesanan.saya') . '">Kembali</a>', false)
            ->assertDontSee('history.back', false);
    }

    public function test_struk_delivery_belum_bayar_tidak_menampilkan_input_untuk_admin(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->actingAs($this->admin, 'web')->get(route('pesanan.struk', $id))
            ->assertOk()->assertDontSee('data-bayar-input', false);
    }

    public function test_pembeli_hanya_boleh_membuka_struk_pesanannya_sendiri(): void
    {
        $idSaya  = $this->buatPesanan('PICKUP', 'DIPROSES', 75000, $this->pembeli);
        $idOrang = $this->buatPesanan('PICKUP', 'DIPROSES', 75000, $this->pembeliLain);

        $this->actingAs($this->pembeli, 'pembeli')->get(route('pesanan.struk', $idSaya))->assertOk();
        $this->actingAs($this->pembeli, 'pembeli')->get(route('pesanan.struk', $idOrang))->assertForbidden();
    }

    public function test_pembeli_tidak_melihat_input_pembayaran_di_struk(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->actingAs($this->pembeli, 'pembeli')->get(route('pesanan.struk', $id))
            ->assertOk()->assertDontSee('data-bayar-input', false);
    }

    public function test_struk_butuh_login(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->get(route('pesanan.struk', $id))->assertRedirect();
    }

    // ---------- RIWAYAT PESANAN PEMBELI ----------

    public function test_riwayat_pembeli_menampilkan_uang_diterima_kembalian_dan_tombol_struk(): void
    {
        $dibayar = $this->buatPesanan('PICKUP');
        $this->bayar($this->admin, $dibayar, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 100000]);
        $belum = $this->buatPesanan('PICKUP', 'DIKONFIRMASI', 40000);

        $res = $this->actingAs($this->pembeli, 'pembeli')->get(route('pesanan.saya'));

        $res->assertOk()
            ->assertSee('Total Pembayaran')
            ->assertSee('Uang diterima')
            ->assertSee('Rp 100.000')
            ->assertSee('Uang kembalian')
            ->assertSee('Rp 25.000')
            ->assertSee(route('pesanan.struk', $dibayar), false)
            // pesanan yang belum dibayar tidak punya tombol struk
            ->assertDontSee(route('pesanan.struk', $belum), false);
    }

    public function test_riwayat_pembeli_tidak_menampilkan_pembayaran_milik_orang_lain(): void
    {
        $idOrang = $this->buatPesanan('PICKUP', 'DIPROSES', 75000, $this->pembeliLain);
        $this->bayar($this->admin, $idOrang, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 123000]);

        $this->actingAs($this->pembeli, 'pembeli')->get(route('pesanan.saya'))
            ->assertOk()->assertDontSee('123.000')->assertDontSee(route('pesanan.struk', $idOrang), false);
    }

    // ---------- JAM LAPORAN OWNER ----------

    public function test_jam_dicetak_pada_laporan_owner_memakai_waktu_wib(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));

        foreach (['cetak', 'excel'] as $format) {
            $sebelum = now('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:');

            $res = $this->actingAs($this->owner, 'web')
                ->get(route('owner.laporan.unduh', ['format' => $format]))
                ->assertOk();

            $sesudah = now('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:');
            $isi = $res->getContent();

            $this->assertTrue(
                str_contains($isi, 'Dicetak ' . $sebelum) || str_contains($isi, 'Dicetak ' . $sesudah),
                "Jam 'Dicetak' pada format {$format} tidak sama dengan waktu WIB sekarang."
            );
        }
    }

    public function test_halaman_cetak_laporan_punya_tombol_kembali_dengan_periode_sama(): void
    {
        $query = ['dari' => '2026-11-01', 'sampai' => '2026-11-15', 'top' => 5];
        $kembali = e(route('owner.laporan', $query));

        $this->actingAs($this->owner, 'web')
            ->get(route('owner.laporan.unduh', $query + ['format' => 'cetak']))
            ->assertOk()
            ->assertSee('Kembali ke Laporan')
            ->assertSee('href="' . $kembali . '"', false);

        // Excel berupa unduhan file, tidak perlu tombol kembali
        $this->actingAs($this->owner, 'web')
            ->get(route('owner.laporan.unduh', $query + ['format' => 'excel']))
            ->assertOk()
            ->assertDontSee('Kembali ke Laporan');
    }

    // ---------- PANEL KURIR & ADMIN ----------

    public function test_panel_kurir_menampilkan_form_lalu_ringkasan_pembayaran(): void
    {
        $id = $this->buatPesanan('DELIVERY');
        $this->tugaskan($id, $this->kurir);

        $this->actingAs($this->kurir, 'web')->get(route('kurir.pesanan'))
            ->assertOk()
            ->assertSee('data-bayar-input', false)
            ->assertSee('Catat pembayaran dulu')
            ->assertDontSee('Update Status: Selesai');

        $this->bayar($this->kurir, $id, ['tempat_pembayaran' => 'ALAMAT_PEMBELI', 'uang_diterima' => 100000]);

        $this->actingAs($this->kurir, 'web')->get(route('kurir.pesanan'))
            ->assertOk()
            ->assertDontSee('data-bayar-input', false)
            ->assertSee('Rp 100.000')
            ->assertSee('Rp 25.000')
            ->assertSee('Update Status: Selesai')
            ->assertSee(route('pesanan.struk', $id), false);
    }

    public function test_halaman_admin_pesanan_tetap_tampil_dan_menautkan_ke_struk_untuk_pickup(): void
    {
        $id = $this->buatPesanan('PICKUP');

        $this->actingAs($this->admin, 'web')->get(route('admin.pesanan'))
            ->assertOk()
            ->assertSee('Input Pembayaran &amp; Struk', false)
            ->assertDontSee('Catat Pembayaran');

        $this->bayar($this->admin, $id, ['tempat_pembayaran' => 'TOKO', 'uang_diterima' => 90000]);

        $this->actingAs($this->admin, 'web')->get(route('admin.pesanan'))->assertOk();
    }
}
