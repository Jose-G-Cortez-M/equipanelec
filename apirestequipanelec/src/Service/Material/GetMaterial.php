<?php

namespace App\Service\Material;

use App\Entity\Material;
use App\Repository\MaterialRepository;


class GetMaterial
{
    private MaterialRepository $materialRepository;
    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    public function __invoke(int $id): ?Material
    {
        return $this->materialRepository->find($id);
    }
}