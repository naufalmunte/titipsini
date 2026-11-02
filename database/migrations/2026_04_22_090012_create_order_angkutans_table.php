<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_angkutans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Master Armada (Untuk ambil Harga Sewa & Tarif KM)
            $table->foreignId('jenis_armada_id')->constrained('jenis_armadas')->onDelete('cascade');
            
            // Relasi ke Alamat (Titik Jemput & Titik Tujuan)
            $table->foreignId('alamat_jemput_id')->constrained('alamat_pelanggans')->onDelete('cascade');
            $table->foreignId('alamat_tujuan_id')->constrained('alamat_pelanggans')->onDelete('cascade');

            // Data Teknis (Snapshot)
            $table->decimal('jarak_km', 10, 2);
            $table->date('tanggal_angkut');
            $table->time('jam_angkut');
            
            // Info Barang (Hanya Informasi)
            $table->text('catatan_muatan')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_angkutans');
    }
};