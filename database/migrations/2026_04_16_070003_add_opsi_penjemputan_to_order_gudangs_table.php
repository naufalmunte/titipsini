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
        Schema::table('order_gudangs', function (Blueprint $table) {
        $table->enum('opsi_penjemputan', ['jemput', 'antar_sendiri'])
              ->default('antar_sendiri')
              ->after('alamat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_gudangs', function (Blueprint $table) {
            $table->dropColumn('opsi_penjemputan');
        });
    }
};
