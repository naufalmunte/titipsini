<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_kendaraans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel lahan_parkirs (Gudang Kendaraan)
            $table->foreignId('lahan_id')
                  ->nullable()
                  ->constrained('lahan_parkirs')
                  ->nullOnDelete(); 
            
            $table->date('tanggal_mulai'); 
            $table->date('tanggal_selesai'); 
            
            // Kolom virtual untuk hitung selisih hari
            $table->integer('total_hari')->virtualAs('DATEDIFF(tanggal_selesai, tanggal_mulai)');
            
            // Relasi ke alamat_pelanggans
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
        Schema::dropIfExists('order_kendaraans');
    }
};