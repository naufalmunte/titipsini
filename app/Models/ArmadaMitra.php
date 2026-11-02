<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmadaMitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_armada_id',
        'plat_nomor',
        'merek',
        'link_foto',
        'status_ketersediaan',
        'status_verifikasi',
    ];

    // Relasi ke Master Jenis Armada
    public function jenisArmada()
    {
        return $this->belongsTo(JenisArmada::class, 'jenis_armada_id');
    }

    // Relasi ke User (Mitra)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}