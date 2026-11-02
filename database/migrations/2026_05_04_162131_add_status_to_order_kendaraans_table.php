<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_kendaraans', function (Blueprint $table) {
            // Pastikan lahan_id bisa menampung FK ke lahan_parkirs jika belum ada
            if (!Schema::hasColumn('order_kendaraans', 'lahan_id')) {
                $table->foreignId('lahan_id')->nullable()->after('id')->constrained('lahan_parkirs');
            }

            // Tambahkan status yang identik dengan penitipan barang
            $table->enum('status', [
                'menunggu_kedatangan', 
                'dititip', 
                'masa_titip_habis', 
                'diambil', 
                'selesai'
            ])->default('menunggu_kedatangan')->after('catatan_pelanggan');
        });
    }

    public function down(): void
    {
        Schema::table('order_kendaraans', function (Blueprint $table) {
            $table->dropForeign(['lahan_id']);
            $table->dropColumn(['lahan_id', 'status']);
        });
    }
};