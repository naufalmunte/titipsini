<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gudang_penitipans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Pemilik (User/Mitra)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data Utama Gudang
            $table->string('nama_gudang');
            $table->text('deskripsi')->nullable(); // Posisi setelah nama
            
            // Detail Alamat (Pecah agar Finance gampang filter)
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('kecamatan');
            $table->text('alamat_lengkap');
            
            // Spesifikasi & Status
            $table->string('link_foto')->nullable();
            $table->string('ukuran_gudang');
            $table->boolean('is_aktif')->default(true); // Saklar Utama Gudang
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gudang_penitipans');
    }
};