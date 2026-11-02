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
    Schema::table('verifies', function (Blueprint $table) {
        // Kita tambah status (default pending) dan catatan (untuk alasan penolakan)
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('pin');
        $table->text('catatan')->nullable()->after('status'); 
    });
}

public function down(): void
{
    Schema::table('verifies', function (Blueprint $table) {
        $table->dropColumn(['status', 'catatan']);
    });
}
};
