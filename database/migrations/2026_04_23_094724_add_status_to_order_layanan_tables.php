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
    // Status untuk Angkutan
    Schema::table('order_angkutans', function (Blueprint $table) {
        $table->enum('status', [
            'menunggu_jemputan',
            'proses_angkut',
            'selesai'
        ])->nullable()->after('catatan_muatan');
    });

    // Status untuk Gudang
    Schema::table('order_gudangs', function (Blueprint $table) {
        $table->enum('status', [
            'menunggu_kedatangan',
            'dititip',
            'masa_titip_habis',
            'diambil',
            'selesai'
        ])->nullable()->after('catatan_pelanggan');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_layanan_tables', function (Blueprint $table) {
            //
        });
    }
};
