<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('armada_mitras', function (Blueprint $table) {
            // Menambahkan kolom tepat setelah kolom status_ketersediaan biar rapi
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])
                  ->default('pending')
                  ->after('status_ketersediaan');
                  
            $table->boolean('is_aktif')
                  ->default(false)
                  ->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('armada_mitras', function (Blueprint $table) {
            // Hapus kolom kalau migration di-rollback
            $table->dropColumn(['is_aktif', 'status_verifikasi']);
        });
    }
};