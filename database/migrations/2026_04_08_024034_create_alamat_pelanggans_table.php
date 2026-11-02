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
        Schema::create('alamat_pelanggans', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (milik pelanggan siapa?)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('kecamatan');
            $table->text('alamat_lengkap');
            $table->text('catatan_alamat')->nullable(); // Contoh: "Pagar hitam, sebelah laundry"
            
            // Kolom sakti untuk menandai alamat mana yang jadi pilihan utama
            $table->boolean('is_utama')->default(false); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alamat_pelanggans');
    }
};
