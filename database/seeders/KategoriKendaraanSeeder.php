<?php

namespace Database\Seeders;

use App\Models\KategoriKendaraan;
use Illuminate\Database\Seeder;

class KategoriKendaraanSeeder extends Seeder
{
    public function run(): void
    {
        KategoriKendaraan::create([
            'nama_kategori' => 'Roda 2',
            'harga_per_hari' => 10000,
            'deskripsi' => 'Penitipan untuk motor roda dua (bebek, matic, sport)',
            'icon' => 'fa-motorcycle'
        ]);

        KategoriKendaraan::create([
            'nama_kategori' => 'Roda 4',
            'harga_per_hari' => 25000,
            'deskripsi' => 'Penitipan untuk mobil pribadi, SUV, atau MPV',
            'icon' => 'fa-car'
        ]);
    }
}