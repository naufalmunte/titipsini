<?php

namespace App\Services;

use App\Repositories\ArmadaMitraRepository;
use Illuminate\Support\Facades\Storage;

class ArmadaMitraService
{
    protected $repo;

    public function __construct(ArmadaMitraRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listArmadaMitra($userId)
    {
        return $this->repo->getByUserId($userId);
    }

    public function simpanArmada($userId, array $data, $foto = null)
    {
        $data['user_id'] = $userId;
        $data['status_ketersediaan'] = 'tersedia'; // Status awal mobil

        if ($foto) {
            $path = $foto->store('armada_fotos', 'public');
            $data['link_foto'] = $path;
        }

        return $this->repo->create($data);
    }

    public function updateArmada($id, array $data, $foto = null)
    {
        if ($foto) {
            $armada = $this->repo->findById($id);
            if ($armada->link_foto) {
                Storage::disk('public')->delete($armada->link_foto);
            }
            $path = $foto->store('armada_fotos', 'public');
            $data['link_foto'] = $path;
        }

        return $this->repo->update($id, $data);
    }

    public function hapusArmada($id)
    {
        $armada = $this->repo->findById($id);
        if ($armada->link_foto) {
            Storage::disk('public')->delete($armada->link_foto);
        }
        return $this->repo->delete($id);
    }

    // app/Services/ArmadaMitraService.php

    public function listSemuaArmada()
    {
        return $this->repo->getAllWithUser();
    }

    public function verifikasiArmada($id, $status)
    {
        $data = [
            'status_verifikasi' => $status,
            'is_aktif' => ($status === 'disetujui') // Otomatis true jika disetujui, false jika ditolak
        ];

        return $this->repo->update($id, $data);
    }
}