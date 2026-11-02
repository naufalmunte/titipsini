<?php

namespace App\Repositories;

use App\Models\ArmadaMitra;

class ArmadaMitraRepository
{
    public function getByUserId($userId)
    {
        // Mengambil mobil milik mitra beserta info jenis armadanya
        return ArmadaMitra::with('jenisArmada')
                ->where('user_id', $userId)
                ->latest()
                ->paginate(10);
    }

    public function create(array $data)
    {
        return ArmadaMitra::create($data);
    }

    public function findById($id)
    {
        return ArmadaMitra::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $armada = $this->findById($id);
        $armada->update($data);
        return $armada;
    }

    public function delete($id)
    {
        $armada = $this->findById($id);
        return $armada->delete();
    }

    // app/Repositories/ArmadaMitraRepository.php

    public function getAllWithUser()
    {
        // Mengambil semua armada beserta data Pemilik (User) dan Jenis Mobilnya
        return ArmadaMitra::with(['user', 'jenisArmada'])->latest()->paginate(10);
    }

    public function getPendingArmada()
    {
        // Khusus untuk dashboard Admin agar fokus ke yang belum diverifikasi
        return ArmadaMitra::with(['user', 'jenisArmada'])
                ->where('status_verifikasi', 'pending')
                ->latest()
                ->get();
    }
}