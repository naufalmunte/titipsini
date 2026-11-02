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
        Schema::create('order_gudang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_gudang_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_barang_id')->constrained()->restrictOnDelete();
            $table->string('nama_barang_spesifik')->nullable(); // Cth: Kulkas Sharp 2 Pintu
            $table->integer('jumlah'); 
            $table->decimal('harga_per_barang_saat_ini', 12, 2)->default(0); // Harga 'mati' saat checkout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_gudang_items');
    }
};
