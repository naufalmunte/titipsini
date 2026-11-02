<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBangunanItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke OrderBangunan
    public function orderBangunan()
    {
        return $this->belongsTo(OrderBangunan::class, 'order_bangunan_id');
    }

    // Relasi ke KategoriBangunan
    public function kategori()
    {
        return $this->belongsTo(KategoriBangunan::class, 'kategori_id');
    }

    public function alamatPelanggan()
{
    return $this->belongsTo(AlamatPelanggan::class, 'alamat_id');
}   
}