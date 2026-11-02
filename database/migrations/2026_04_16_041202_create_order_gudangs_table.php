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
    Schema::create('order_gudangs', function (Blueprint $table) {
        $table->id();
        
        // Sesuaikan ke gudang_penitipans
        $table->foreignId('gudang_id')
              ->nullable()
              ->constrained('gudang_penitipans')
              ->nullOnDelete(); 
        
        $table->date('tanggal_mulai'); 
        $table->date('tanggal_selesai'); 
        
        // Gunakan raw query untuk virtual column agar lebih aman
        $table->integer('total_hari')->virtualAs('DATEDIFF(tanggal_selesai, tanggal_mulai)');
        
        // Sesuaikan ke alamat_pelanggans
        $table->foreignId('alamat_id')
              ->nullable()
              ->constrained('alamat_pelanggans')
              ->nullOnDelete(); 

        $table->text('catatan_pelanggan')->nullable(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_gudangs');
    }
};
