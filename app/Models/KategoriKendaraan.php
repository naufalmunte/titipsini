<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriKendaraan extends Model
{
    // Mengizinkan semua kolom diisi (karena tabelnya sederhana)
    protected $guarded = [];

    /**
     * Relasi ke detail item pesanan
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderKendaraanItem::class, 'kategori_id');
    }
}