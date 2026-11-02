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
        Schema::table('jenis_armadas', function (Blueprint $table) {
            $table->decimal('tarif_per_km', 12, 2)->after('harga_sewa')->default(5000);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_armadas', function (Blueprint $table) {
            //
        });
    }
};
