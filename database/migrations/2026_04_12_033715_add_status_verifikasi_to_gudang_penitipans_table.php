<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gudang_penitipans', function (Blueprint $table) {
            // Tambah kolom status_verifikasi dengan nilai default 'pending'
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('is_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('gudang_penitipans', function (Blueprint $table) {
            $table->dropColumn('status_verifikasi');
        });
    }
};