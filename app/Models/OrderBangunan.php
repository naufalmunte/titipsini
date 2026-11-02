<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBangunan extends Model
{
    protected $guarded = ['id'];

    /**
     * Relasi ke Alamat Pelanggan
     * Menghubungkan alamat_id di tabel ini ke tabel alamat_pelanggans
     */
    public function alamatPelanggan() 
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_id');
    }

    /**
     * Relasi ke penjaga (User dengan role Mitra)
     * Kita arahkan ke User::class karena Mitra adalah User
     */

    // Relasi ke items aset
    public function items() {
        return $this->hasMany(OrderBangunanItem::class, 'order_bangunan_id');
    }

    // Relasi Polymorphic ke Order Pusat
    public function order() {
        return $this->morphOne(Order::class, 'layanan');
    }

    public function alamat()
    {
        return $this->belongsTo(alamatPelanggan::class, 'alamat_id');
    }
   public function penjaga()
    {
        // Jika di tabel order_gudangs kamu memiliki kolom 'penjaga_id'
        return $this->belongsTo(PenjagaBangunan::class, 'penjaga_id');
        
        // ATAU jika di tabel penjaga_bangunans yang menyimpan 'order_gudang_id', gunakan:
        // return $this->hasOne(PenjagaBangunan::class, 'order_gudang_id');
    }

}