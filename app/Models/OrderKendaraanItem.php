<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderKendaraanItem extends Model
{
    protected $guarded = [];

    public function orderKendaraan(): BelongsTo
    {
        return $this->belongsTo(OrderKendaraan::class, 'order_kendaraan_id');
    }

    public function kategori()
    {
        // Pastikan nama model Kategori kamu sudah benar (misal: Kategori atau Category)
        return $this->belongsTo(KategoriKendaraan::class, 'kategori_id');
    }
}