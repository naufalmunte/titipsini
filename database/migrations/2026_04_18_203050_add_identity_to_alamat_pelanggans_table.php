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
        Schema::table('alamat_pelanggans', function (Blueprint $table) {
            // Kita tambahkan kolom nama_penerima dan telepon.
            // Gunakan nullable() agar jika sudah ada data lama, migrasinya tidak error.
            $table->string('nama_penerima')->after('user_id')->nullable();
            $table->string('telepon')->after('nama_penerima')->nullable();
            
            // Opsional: Jika kamu ingin kolom provinsi juga ada (karena di modal tadi ada)
            if (!Schema::hasColumn('alamat_pelanggans', 'provinsi')) {
                $table->string('provinsi')->after('telepon')->default('Sumatera Barat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat_pelanggans', function (Blueprint $table) {
            $table->dropColumn(['nama_penerima', 'telepon', 'provinsi']);
        });
    }
};