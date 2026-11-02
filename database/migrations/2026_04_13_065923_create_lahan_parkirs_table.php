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
        Schema::create('lahan_parkirs', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users (Mitra)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Data Lokasi & Info
            $table->string('nama_lahan');
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('kecamatan');
            $table->text('alamat_lengkap'); // Tambahan kalau butuh alamat detail
            
            // Kapasitas Kendaraan
            $table->integer('kapasitas_roda_2')->default(0);
            $table->integer('kapasitas_roda_3')->default(0);
            $table->integer('kapasitas_roda_4')->default(0);
            
            // Status Verifikasi & Operasional (Persis seperti Gudang)
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->boolean('is_aktif')->default(false);
            
            // Bukti Foto
            $table->string('link_foto')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan_parkirs');
    }
};