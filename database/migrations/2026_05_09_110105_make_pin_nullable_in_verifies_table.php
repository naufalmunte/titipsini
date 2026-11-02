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
        $table->string('pin')->nullable()->change(); // JEDER! Jadi boleh kosong
    });
}

public function down(): void
{
    Schema::table('verifies', function (Blueprint $table) {
        $table->string('pin')->nullable(false)->change();
    });
}
};
