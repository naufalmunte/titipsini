<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armada_mitras', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Pemilik (Mitra)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Relasi ke Master Jenis Armada
            $table->foreignId('jenis_armada_id')->constrained('jenis_armadas')->cascadeOnDelete();
            
            // Data Kendaraan
            $table->string('plat_nomor')->unique();
            $table->string('merek');
            $table->string('link_foto')->nullable();
            $table->enum('status_ketersediaan', ['tersedia', 'sedang_jalan', 'maintenance'])->default('tersedia');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armada_mitras');
    }
};