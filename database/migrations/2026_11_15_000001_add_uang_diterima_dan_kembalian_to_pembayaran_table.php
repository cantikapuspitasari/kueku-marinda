<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah data uang tunai yang diterima dan kembalian pada tabel pembayaran.
     * Tabel pembayaran sendiri dibuat dari schema_kuekumarinda.sql, jadi di sini
     * hanya kolom baru yang ditambahkan (dengan pengecekan agar aman dijalankan ulang).
     */
    public function up(): void
    {
        if (! Schema::hasTable('pembayaran')) {
            return;
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            if (! Schema::hasColumn('pembayaran', 'uang_diterima')) {
                // Nullable supaya baris lama tidak error; diisi ulang di bawah.
                $table->decimal('uang_diterima', 10, 2)->nullable()->after('jumlah_bayar');
            }

            if (! Schema::hasColumn('pembayaran', 'kembalian')) {
                $table->decimal('kembalian', 10, 2)->default(0)->after('uang_diterima');
            }
        });

        // Pembayaran lama dicatat pas sebesar total pesanan (belum ada fitur kembalian),
        // jadi uang diterima = jumlah bayar dan kembalian = 0.
        DB::table('pembayaran')
            ->whereNull('uang_diterima')
            ->update([
                'uang_diterima' => DB::raw('jumlah_bayar'),
                'kembalian'     => 0,
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('pembayaran')) {
            return;
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'kembalian')) {
                $table->dropColumn('kembalian');
            }

            if (Schema::hasColumn('pembayaran', 'uang_diterima')) {
                $table->dropColumn('uang_diterima');
            }
        });
    }
};
