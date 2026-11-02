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
        // Kita ubah kolom status agar menerima pilihan baru
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending_assignment', 'menunggu_pembayaran', 'pending_confirmation', 'dibayar', 'diproses', 'dititip', 'selesai', 'dibatalkan')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
