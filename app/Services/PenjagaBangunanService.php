<?php

namespace App\Services;

use App\Repositories\PenjagaBangunanRepository;

class PenjagaBangunanService
{
    protected $penjagaRepo;

    // Langsung inject class Repository-nya
    public function __construct(PenjagaBangunanRepository $penjagaRepo)
    {
        $this->penjagaRepo = $penjagaRepo;
    }

    public function getPenjagaByMitra($userId)
    {
        return $this->penjagaRepo->getAllByMitra($userId);
    }

    public function storePenjaga($userId, array $data)
    {
        // Otomatis tambahkan data wajib dari sistem
        $data['user_id'] = $userId;
        $data['status_ketersediaan'] = 'tersedia';
        $data['status_verifikasi'] = 'pending'; 
        
        return $this->penjagaRepo->create($data);
    }

    public function deletePenjaga($id)
    {
        return $this->penjagaRepo->delete($id);
    }
}