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
    // Kita pakai query manual biar MySQL nggak protes soal data lama
    DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
        'menunggu_konfirmasi_admin', 
        'menunggu_pembayaran', 
        'menunggu_konfirmasi_pembayaran', 
        'pembayaran_ditolak', 
        'diproses', 
        'selesai'
    ) NOT NULL DEFAULT 'menunggu_konfirmasi_admin'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
