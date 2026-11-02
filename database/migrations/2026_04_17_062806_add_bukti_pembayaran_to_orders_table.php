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
    Schema::table('orders', function (Blueprint $table) {
        // Kita pakai string untuk simpan 'path/alamat' filenya saja
        // nullable() karena saat pertama booking, bukti bayar kan belum ada
        $table->string('bukti_pembayaran')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('bukti_pembayaran');
    });
}
};
