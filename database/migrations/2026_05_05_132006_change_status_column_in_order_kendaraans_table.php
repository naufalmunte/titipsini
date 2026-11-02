<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('order_kendaraans', function (Blueprint $table) {
        // Kita gunakan DB::statement karena mengganti varchar ke ENUM 
        // terkadang error jika hanya menggunakan ->change() biasa
        DB::statement("ALTER TABLE order_kendaraans MODIFY status ENUM(
            'menunggu_kedatangan', 
            'dititip', 
            'masa_titip_habis', 
            'diambil', 
            'selesai'
        ) NULL DEFAULT NULL");
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_kendaraans', function (Blueprint $table) {
            //
        });
    }
};
