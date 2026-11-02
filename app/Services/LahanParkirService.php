<?php

namespace App\Services;

use App\Repositories\LahanParkirRepository;

class LahanParkirService
{
    protected $repo;

    public function __construct(LahanParkirRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listParkirMitra($userId)
    {
        return $this->repo->getByUserId($userId);
    }

    public function simpanParkir($userId, array $data)
    {
        $data['user_id'] = $userId;
        // Lahan baru otomatis mati dan menunggu verifikasi admin
        $data['is_aktif'] = false; 
        $data['status_verifikasi'] = 'pending';

        return $this->repo->create($data);
    }

    public function updateParkir($id, array $data)
    {
        // Mencegah mitra memanipulasi status verifikasi secara paksa
        unset($data['status_verifikasi']); 
        return $this->repo->update($id, $data);
    }

    public function toggleStatus($id)
    {
        $parkir = $this->repo->findById($id);
        
        // Cek keamanan: Hanya lahan yang 'disetujui' admin yang bisa buka/tutup
        if ($parkir->status_verifikasi === 'disetujui') {
            return $this->repo->update($id, [
                'is_aktif' => !$parkir->is_aktif
            ]);
        }
        
        return false;
    }

    public function hapusParkir($id)
    {
        return $this->repo->delete($id);
    }

    // --- FUNGSI KHUSUS ADMIN ---
    
    public function listSemuaParkir()
    {
        // Mengambil semua lahan parkir milik semua mitra (beserta data usernya)
        return $this->repo->getAllWithUser();
    }

    public function verifikasiParkir($id, $status)
    {
        // Jika disetujui, otomatis aktif. Jika ditolak/pending, otomatis mati.
        $data = [
            'status_verifikasi' => $status,
            'is_aktif'          => ($status === 'disetujui') ? true : false
        ];
        
        return $this->repo->update($id, $data);
    }
}