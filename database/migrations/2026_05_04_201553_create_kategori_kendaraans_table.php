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
        Schema::create('kategori_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Contoh: Roda 2, Roda 4
            $table->decimal('harga_per_hari', 12, 2); // Harga sewa harian
            $table->text('deskripsi')->nullable(); // Penjelasan singkat
            $table->string('icon')->nullable(); // Untuk tampilan di UI (FontAwesome)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_kendaraans');
    }
};
