<?php

namespace App\Services;

use App\Repositories\GudangRepository;

class GudangService
{
    protected $repo;

    public function __construct(GudangRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listGudangMitra($userId)
    {
        return $this->repo->getByUserId($userId);
    }

    public function simpanGudang($userId, array $data)
    {
        $data['user_id'] = $userId;
        $data['is_aktif'] = false; // Otomatis mati sampai di-acc admin
        $data['status_verifikasi'] = 'pending';

        return $this->repo->create($data);
    }

    // FUNGSI BARU UNTUK EDIT
    public function updateGudang($id, array $data)
    {
        // Mencegah mitra memanipulasi status verifikasi secara paksa
        unset($data['status_verifikasi']); 
        return $this->repo->update($id, $data);
    }

    // FUNGSI BARU UNTUK HAPUS
    public function hapusGudang($id)
    {
        return $this->repo->delete($id);
    }

    // Tambahkan 2 fungsi ini untuk Admin
    public function listSemuaGudang()
    {
        return $this->repo->getAllWithUser();
    }

    public function verifikasiGudang($id, $status)
    {
        // Jika statusnya disetujui, maka is_aktif langsung true. Jika ditolak/pending, is_aktif false.
        $data = [
            'status_verifikasi' => $status,
            'is_aktif'          => ($status === 'disetujui') ? true : false
        ];
        
        return $this->repo->update($id, $data);
    }

    // FUNGSI BARU UNTUK TOGGLE BUKA/TUTUP
    public function toggleStatus($id)
    {
        $gudang = $this->repo->findById($id);
        
        // Cek keamanan: Hanya gudang yang 'disetujui' admin yang bisa buka/tutup
        if ($gudang->status_verifikasi === 'disetujui') {
            return $this->repo->update($id, [
                'is_aktif' => !$gudang->is_aktif // Membalik nilai true/false
            ]);
        }
        
        return false;
    }
}