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
        Schema::create('kategori_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Cth: Elektronik Besar
            $table->string('slug')->unique(); 
            $table->text('deskripsi')->nullable();
            $table->decimal('tarif_per_barang', 12, 2)->default(0); // Harga per item / hari
            $table->string('icon')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_barangs');
    }
};
