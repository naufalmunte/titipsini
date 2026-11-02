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
    Schema::table('profil_mitra', function (Blueprint $table) {
        // Kita letakkan provinsi, kab_kota, dan kecamatan SEBELUM alamat
        // Asumsi: kolom sebelum 'alamat' adalah 'nama_pt'
        $table->string('provinsi')->nullable()->after('nama_pt');
        $table->string('kab_kota')->nullable()->after('provinsi');
        $table->string('kecamatan')->nullable()->after('kab_kota');
        
        // Untuk memindahkan 'alamat' ke paling akhir, kita gunakan change() 
        // (Membutuhkan package: composer require doctrine/dbal)
        $table->text('alamat')->nullable()->change()->after('kecamatan');
    });
}

    public function down()
    {
        Schema::table('profil_mitra', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kab_kota', 'kecamatan']);
        });
    }
};
