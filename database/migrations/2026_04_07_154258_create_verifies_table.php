<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifies', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (jika user dihapus, data verifikasi ikut hapus)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->date('tgl_lahir');
            $table->string('link_identitas'); // Untuk link Google Drive kamu
            $table->string('pin', 6); // PIN 6 digit
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifies');
    }
};