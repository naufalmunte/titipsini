<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rekening extends Model
{
    protected $fillable = [
        'user_id', 
        'nama_bank', 
        'nomor_rekening', 
        'atas_nama', 
        'is_utama'
    ];

    /**
     * JEDER! Relasi ke User (Pemilik Rekening)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}