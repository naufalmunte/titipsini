<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // JEDER! Import DB

return new class extends Migration
{
    public function up(): void
    {
        // Cara paling aman untuk update ENUM di MySQL
        DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak', 'selesai') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'");
    }
};