<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGudang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi balik ke tabel Order (Pusat)
     */
public function order()
{
    return $this->morphOne(Order::class, 'layanan');
}

    /**
     * Relasi ke item-item barang yang dititipkan
     */
public function items()
{
    // Di Gambar 4, tabel order_gudang_items punya kolom 'order_gudang_id'
    return $this->hasMany(OrderGudangItem::class, 'order_gudang_id', 'id');
}

    /**
     * Relasi ke Gudang yang dipilih (Gudang Penitipan)
     */
    public function gudang()
    {
        return $this->belongsTo(GudangPenitipan::class, 'gudang_id');
    }

    /**
     * Relasi ke Alamat Jemput
     */
    public function alamat()
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_id');
    }
}