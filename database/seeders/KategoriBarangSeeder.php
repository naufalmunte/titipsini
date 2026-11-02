<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriBarangSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Elektronik Besar',
                'tarif' => 15000, // Rp 15.000 / hari
                'icon' => 'tv'
            ],
            [
                'nama' => 'Furniture & Mebel',
                'tarif' => 20000, // Rp 20.000 / hari
                'icon' => 'armchair'
            ],
            [
                'nama' => 'Kardus / Box Sedang',
                'tarif' => 5000,  // Rp 5.000 / hari
                'icon' => 'box'
            ],
            [
                'nama' => 'Kendaraan Roda 2',
                'tarif' => 25000, // Rp 25.000 / hari
                'icon' => 'bike'
            ],
        ];

        foreach ($categories as $cat) {
            KategoriBarang::create([
                'nama_kategori' => $cat['nama'],
                'slug' => Str::slug($cat['nama']),
                'tarif_per_barang' => $cat['tarif'],
                'icon' => $cat['icon'],
                'deskripsi' => 'Layanan penitipan ' . $cat['nama'] . ' dengan keamanan terjamin.'
            ]);
        }
    }
}