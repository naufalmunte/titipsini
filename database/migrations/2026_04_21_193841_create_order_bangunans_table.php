<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_bangunans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel penjaga_bangunans (Layanan Jasa Penjaga)
            $table->foreignId('penjaga_id')
                  ->nullable()
                  ->constrained('penjaga_bangunans')
                  ->nullOnDelete(); 
            
            $table->date('tanggal_mulai'); 
            $table->date('tanggal_selesai'); 
            
            // Menghitung selisih hari otomatis
            $table->integer('total_hari')->virtualAs('DATEDIFF(tanggal_selesai, tanggal_mulai)');
            
            // Relasi ke lokasi alamat pelanggan (Bangunan yang mau dijaga)
            $table->foreignId('alamat_id')
                  ->nullable()
                  ->constrained('alamat_pelanggans')
                  ->nullOnDelete(); 

            $table->text('catatan_pelanggan')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_bangunans');
    }
};