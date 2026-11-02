<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_armadas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis'); // Cth: Pickup Buka, Truk Engkel
            $table->decimal('harga_sewa', 12, 2); // Cth: 300000
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_armadas');
    }
};