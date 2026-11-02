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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pelanggan
            $table->foreignId('mitra_id')->nullable()->constrained('users')->nullOnDelete(); // Diisi Admin nanti
            $table->morphs('layanan'); // Membuat layanan_type & layanan_id
            $table->enum('status', ['pending_assignment', 'menunggu_pembayaran', 'dibayar', 'diproses', 'selesai', 'dibatalkan'])->default('pending_assignment');
            $table->decimal('total_bayar', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
