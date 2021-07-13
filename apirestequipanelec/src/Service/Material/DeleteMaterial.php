<?php

namespace App\Service\Material;
use Exception;
use App\Repository\MaterialRepository;


class DeleteMaterial
{
    private MaterialRepository $materialRepository;
    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    public function __invoke(int $id)
    {
        $material = $this->materialRepository->find($id);
        if(!$material){
            throw new Exception('Este material no existe');
        }
        $this->materialRepository->delete($material);

    }
}