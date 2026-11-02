<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OrderKendaraan extends Model
{
    protected $guarded = [];

    // Relasi ke Tabel Order Pusat (Polymorphic)
    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'layanan');
    }

    // Relasi ke Item Kendaraan (Unit-unitnya)
    public function items(): HasMany
    {
        return $this->hasMany(OrderKendaraanItem::class, 'order_kendaraan_id');
    }

    // Relasi ke Lahan Parkir (Tempat Penitipan)
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(LahanParkir::class, 'lahan_id');
    }

    // Relasi ke Alamat Pelanggan
    public function alamat(): BelongsTo
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_id');
    }
}