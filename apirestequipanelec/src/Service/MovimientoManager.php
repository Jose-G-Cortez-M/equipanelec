<?php

namespace App\Service;

use App\Entity\Movimiento;
use App\Repository\MovimientoRepository;
use Doctrine\ORM\EntityManagerInterface;

class MovimientoManager
{

    private $em;
    private $movimientoRepository;

    public function __construct(EntityManagerInterface $em, MovimientoRepository $movimientoRepository)
    {
        $this->em = $em;
        $this->movimientoRepository = $movimientoRepository;
    }

    public function find(int $id): ?Movimiento
    {
        return $this->movimientoRepository->find($id);
    }

    public function getRepository(): MovimientoRepository
    {
        return $this->movimientoRepository;
    }

    public function create(): Movimiento
    {
        $movimiento = new Movimiento();
        return $movimiento;
    }

    public function persist(Movimiento $movimiento): Movimiento
    {
        $this->em->persist($movimiento);
        return $movimiento;
    }

    public function save(Movimiento $movimiento): Movimiento
    {
        $this->em->persist($movimiento);
        $this->em->flush();
        return $movimiento;
    }

    public function reload(Movimiento $movimiento): Movimiento
    {
        $this->em->refresh($movimiento);
        return $movimiento;
    }
}