<?php

namespace App\Service\Material;

use App\Entity\Material;
use Ramsey\Uuid\Nonstandard\Uuid;
use App\Repository\MaterialRepository;
use App\Model\Exception\Material\MaterialNotFound;

class GetMaterial
{
    private MaterialRepository $materialRepository;

    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    public function __invoke(string $id): Material
    {
        $material = $this->materialRepository->find(Uuid::fromString($id)); 
        if (!$material) {
            MaterialNotFound::throwException();
        }
        return $material;
    }
}