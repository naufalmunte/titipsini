<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
    'tanggal_selesai' => 'date',
    'tanggal_mulai' => 'date',
];

protected $fillable = [
    'invoice_number',
    'user_id',
    'mitra_id',      // <--- WAJIB TAMBAHKAN INI, RAJA!
    'layanan_type',
    'layanan_id',
    'status',
    'bukti_pembayaran',
    'total_bayar',
    'total_denda',
];

    // Jantung Polymorphic: Bisa jadi OrderGudang atau OrderKendaraan
    public function layanan()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Shortcut khusus untuk Titip Barang (Gudang)
    public function orderGudang()
    {
        return $this->belongsTo(OrderGudang::class, 'layanan_id');
    }

    // Shortcut khusus untuk Titip Kendaraan (Lahan Parkir)
    // Tambahkan ini supaya konsisten, Raja!
    public function orderKendaraan()
    {
        return $this->belongsTo(OrderKendaraan::class, 'layanan_id');
    }

    // Shortcut khusus untuk Titip Bangunan
    public function orderBangunan()
    {
        return $this->belongsTo(OrderBangunan::class, 'layanan_id');
    }
}