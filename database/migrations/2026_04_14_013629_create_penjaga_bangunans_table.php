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
        Schema::create('penjaga_bangunans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Mitra yang mendaftarkan penjaga ini
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Data Pribadi Penjaga
            $table->string('nama_penjaga');
            $table->string('no_telp');
            $table->date('tgl_lahir');
            
            // Detail Alamat Domisili Penjaga
            $table->string('provinsi');
            $table->string('kota'); // Kota/Kabupaten
            $table->string('kecamatan');
            $table->text('detail_alamat'); // Nama jalan, RT/RW, Kelurahan, Patokan
            
            // Dokumen Keamanan
            $table->string('link_berkas'); // Link KTP / SKCK / Sertifikat
            
            // Status Operasional & Keamanan
            $table->enum('status_ketersediaan', ['tersedia', 'sedang_bertugas', 'cuti'])->default('tersedia');
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable(); // Alasan jika dokumen ditolak Admin
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjaga_bangunans');
    }
};