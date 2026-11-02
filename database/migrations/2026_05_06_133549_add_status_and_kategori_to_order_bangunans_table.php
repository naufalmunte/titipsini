<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    // 1. Tambah Status ke order_bangunans
    Schema::table('order_bangunans', function (Blueprint $table) {
        $table->enum('status', [
            'menunggu_penjaga', 
            'dalam_pengawasan', 
            'masa_titip_habis', 
            'selesai'
        ])->nullable()->default(null)->after('catatan_pelanggan');
    });

    // 2. Tambah kategori_id ke order_bangunan_items
    Schema::table('order_bangunan_items', function (Blueprint $table) {
        $table->foreignId('kategori_id')
              ->after('order_bangunan_id')
              ->nullable()
              ->constrained('kategori_bangunans')
              ->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_bangunans', function (Blueprint $table) {
            //
        });
    }
};
