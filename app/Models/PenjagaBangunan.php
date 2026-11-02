<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjagaBangunan extends Model
{
    // Mengunci nama tabel jika Laravel otomatis mencari nama jamak yang salah
    protected $table = 'penjaga_bangunans';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // JEDER! Tambahkan fungsi ini agar error relation [mitra] minggat
    public function mitra()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}