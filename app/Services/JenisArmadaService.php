<?php

namespace App\Services;

use App\Repositories\JenisArmadaRepository;

class JenisArmadaService
{
    protected $repo;

    public function __construct(JenisArmadaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllJenis()
    {
        return $this->repo->getAll();
    }

    public function simpanJenis(array $data)
    {
        return $this->repo->create($data);
    }

    public function updateJenis($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function hapusJenis($id)
    {
        return $this->repo->delete($id);
    }
}