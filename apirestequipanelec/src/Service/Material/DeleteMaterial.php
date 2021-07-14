<?php

namespace App\Service\Material;

use App\Repository\MaterialRepository;

class DeleteMaterial
{

    private GetMaterial $getMaterial;
    private MaterialRepository $materialRepository;

    public function __construct(GetMaterial $getMaterial, MaterialRepository $materialRepository)
    {
        $this->getMaterial = $getMaterial;
        $this->materialRepository = $materialRepository;
    }

    public function __invoke(string $id)
    {
        $material = ($this->getMaterial)($id);
        $this->materialRepository->delete($material);
    }
}