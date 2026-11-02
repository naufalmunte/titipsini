<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_bangunan_items', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel ayahnya (order_bangunans)
            $table->foreignId('order_bangunan_id')
                  ->constrained('order_bangunans')
                  ->onDelete('cascade');
            
            $table->string('nama_aset'); // Contoh: Rumah Utama, Gudang Belakang, Lahan Parkir
            $table->text('deskripsi_aset')->nullable(); // Detail tambahan
            
            // Harga sewa penjaga per hari saat transaksi terjadi
            $table->decimal('harga_per_hari_saat_ini', 12, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_bangunan_items');
    }
};