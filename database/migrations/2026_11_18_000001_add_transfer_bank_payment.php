<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Transfer Bank (simulasi). Aman dijalankan walau sebagian kolom sudah dibuat
// lewat database/qris_simulasi.sql atau database/transfer_bank.sql.
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pesanan', 'metode_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $t) {
                $t->string('metode_pembayaran', 10)->default('TUNAI')->after('jenis_pengambilan');
            });
        }
        DB::statement("ALTER TABLE `pesanan` MODIFY `metode_pembayaran` ENUM('TUNAI','QRIS','TRANSFER') NOT NULL DEFAULT 'TUNAI'");

        if (! Schema::hasColumn('pesanan', 'bank_tujuan')) {
            Schema::table('pesanan', function (Blueprint $t) {
                $t->string('bank_tujuan', 10)->nullable()->after('metode_pembayaran');
            });
        }

        if (! Schema::hasColumn('pesanan', 'status_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $t) {
                $t->string('status_pembayaran', 20)->default('BELUM_BAYAR')->after('bank_tujuan');
            });
        }
        DB::statement("ALTER TABLE `pesanan` MODIFY `status_pembayaran` ENUM('BELUM_BAYAR','MENUNGGU_VERIFIKASI','DITOLAK','LUNAS') NOT NULL DEFAULT 'BELUM_BAYAR'");

        if (! Schema::hasColumn('pesanan', 'waktu_bayar')) {
            Schema::table('pesanan', function (Blueprint $t) {
                $t->dateTime('waktu_bayar')->nullable()->after('status_pembayaran');
            });
        }

        if (! Schema::hasTable('bukti_transfer')) {
            Schema::create('bukti_transfer', function (Blueprint $t) {
                $t->id('id_bukti');
                $t->unsignedBigInteger('id_pesanan')->index();
                $t->string('bank', 10);
                $t->bigInteger('nominal');
                $t->string('file_path', 255);
                $t->string('nama_asli', 150)->nullable();
                $t->enum('status', ['MENUNGGU', 'DITERIMA', 'DITOLAK'])->default('MENUNGGU')->index();
                $t->string('catatan_admin', 255)->nullable();
                $t->unsignedBigInteger('id_verifikator')->nullable();
                $t->dateTime('diunggah_pada')->useCurrent();
                $t->dateTime('diverifikasi_pada')->nullable();
            });
        }

        DB::statement("UPDATE `pesanan` p JOIN `pembayaran` y ON y.`id_pesanan` = p.`id_pesanan`
            SET p.`status_pembayaran` = 'LUNAS', p.`waktu_bayar` = COALESCE(p.`waktu_bayar`, y.`tanggal_bayar`)
            WHERE p.`status_pembayaran` = 'BELUM_BAYAR'");
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_transfer');
        DB::statement("UPDATE `pesanan` SET `metode_pembayaran` = 'TUNAI' WHERE `metode_pembayaran` = 'TRANSFER'");
        DB::statement("UPDATE `pesanan` SET `status_pembayaran` = 'BELUM_BAYAR' WHERE `status_pembayaran` IN ('MENUNGGU_VERIFIKASI','DITOLAK')");
        DB::statement("ALTER TABLE `pesanan` MODIFY `metode_pembayaran` ENUM('TUNAI','QRIS') NOT NULL DEFAULT 'TUNAI'");
        DB::statement("ALTER TABLE `pesanan` MODIFY `status_pembayaran` ENUM('BELUM_BAYAR','LUNAS') NOT NULL DEFAULT 'BELUM_BAYAR'");
        if (Schema::hasColumn('pesanan', 'bank_tujuan')) {
            Schema::table('pesanan', fn (Blueprint $t) => $t->dropColumn('bank_tujuan'));
        }
    }
};
