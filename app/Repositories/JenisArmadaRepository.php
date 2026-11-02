<?php

namespace App\Repositories;

use App\Models\JenisArmada;

class JenisArmadaRepository
{
    public function getAll()
    {
        return JenisArmada::latest()->get(); // Tampilkan semua master data dari yang terbaru
    }

    public function create(array $data)
    {
        return JenisArmada::create($data);
    }

    public function update($id, array $data)
    {
        $jenis = JenisArmada::findOrFail($id);
        $jenis->update($data);
        return $jenis;
    }

    public function delete($id)
    {
        $jenis = JenisArmada::findOrFail($id);
        return $jenis->delete();
    }
}