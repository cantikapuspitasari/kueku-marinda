<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom jam_ambil sudah dibuat oleh migration 2026_11_12_085858.
     * Migration ini hanya memastikan kolom ada, supaya tidak error "duplicate column".
     */
    public function up(): void
    {
        if (! Schema::hasColumn('pesanan', 'jam_ambil')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->time('jam_ambil')->nullable()->after('tanggal_ambil');
            });
        }
    }

    public function down(): void
    {
        // Kolom dimiliki migration 2026_11_12_085858, jadi tidak dihapus di sini.
    }
};
