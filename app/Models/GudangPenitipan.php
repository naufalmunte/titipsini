<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPenitipan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi: Satu Gudang milik satu Mitra
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jamOperasional()
    {
        // Berdasarkan Gambar 2, tabel jam_operasional punya kolom user_id
        // Karena satu gudang dimiliki satu user (mitra), kita hubungkan lewat user_id
        return $this->hasMany(JamOperasional::class, 'user_id', 'user_id');
    }

    public function orderGudangs()
    {
        return $this->hasMany(OrderGudang::class, 'gudang_id');
    }
}