<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGudangItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke Parent (Header Order Gudang)
     */
    public function orderGudang()
    {
        return $this->belongsTo(OrderGudang::class);
    }

    /**
     * Relasi ke Master Kategori
     * UBAH NAMA FUNGSI INI DARI kategoriBarang MENJADI kategori
     */
    public function kategori()
    {
        // Pastikan foreign key di tabel order_gudang_items adalah kategori_barang_id
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }
}