<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_kendaraan_items', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel ayahnya (order_kendaraans)
            $table->foreignId('order_kendaraan_id')
                  ->constrained('order_kendaraans')
                  ->onDelete('cascade');
            
            $table->string('jenis_kendaraan'); // motor / mobil
            $table->string('merk_kendaraan');
            $table->string('nomor_plat');
            $table->string('warna')->nullable();
            
            // Snapshot harga dari tabel lahan_parkirs
            $table->decimal('harga_per_hari_saat_ini', 12, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_kendaraan_items');
    }
};