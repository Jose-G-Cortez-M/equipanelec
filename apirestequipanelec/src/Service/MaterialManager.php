<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class MaterialManager
{

    private $em;
    private $materialRepository;

    public function __construct(EntityManagerInterface $em, MaterialRepository $materialRepository)
    {
        $this->em = $em;
        $this->materialRepository = $materialRepository;
    }

    public function find(int $id): ?Material
    {
        return $this->materialRepository->find($id);
    }

    public function getRepository(): MaterialRepository
    {
        return $this->materialRepository;
    }

    public function create(): Material
    {
        $material = new Material();
        return $material;
    }

    public function save(Material $material): Material
    {
        $this->em->persist($material);
        $this->em->flush();
        return $material;
    }
    public function persist(Material $material): Material
    {
        $this->em->persist($material);
        return $material;
    }


    public function reload(Material $material): Material
    {
        $this->em->refresh($material);
        return $material;
    }

    public function delete(Material $material)
    {
        $this->em->remove($material);
        $this->em->flush();
    }
}