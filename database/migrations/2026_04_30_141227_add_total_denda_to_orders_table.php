<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah field denda.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // total_denda: Menyimpan nominal denda keterlambatan harian
            // Kita letakkan setelah total_bayar agar struktur keuangan di database rapi
            $table->decimal('total_denda', 12, 2)->default(0)->after('total_bayar');
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_denda');
        });
    }
};