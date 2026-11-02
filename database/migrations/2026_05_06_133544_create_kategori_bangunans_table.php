<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kategori_bangunans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Rumah, Ruko, Lahan, dll
            $table->decimal('harga_per_hari', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_bangunans');
    }
};
