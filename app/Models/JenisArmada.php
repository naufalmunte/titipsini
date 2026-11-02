<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisArmada extends Model
{
    use HasFactory;

    // Lindungi tabel dengan mendefinisikan kolom yang boleh diisi
    protected $fillable = [
        'nama_jenis',
        'harga_sewa',
        'tarif_km'
    ];

    // Relasi: Satu Jenis Armada (cth: Pickup) bisa dimiliki oleh banyak Armada Mitra
    public function armadaMitras()
    {
        return $this->hasMany(ArmadaMitra::class, 'jenis_armada_id');
    }
}