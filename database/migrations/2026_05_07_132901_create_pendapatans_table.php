<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('pendapatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained('users')->cascadeOnDelete();
            
            // JEDER! Kolom untuk mempermudah filter layanan
            $table->string('kategori_layanan'); // Isinya: 'Bangunan', 'Kendaraan', atau 'Gudang'
            
            $table->decimal('nominal_mitra', 15, 2);     // Porsi 65%
            $table->decimal('nominal_titipsini', 15, 2); // Porsi 35%
            
            $table->enum('status_payout', ['pending', 'dikirim'])->default('pending');
            $table->timestamp('waktu_payout')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendapatans');
    }
};