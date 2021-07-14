<?php

namespace App\Service\Movimiento;

use App\Entity\Movimiento;
use App\Repository\MovimientoRepository;

class CreateMovimiento
{
    private MovimientoRepository $movimientoRepository;

    public function __construct(MovimientoRepository $movimientoRepository)
    {
        $this->movimientoRepository = $movimientoRepository;
    }

    public function __invoke(string $nombre): ?Movimiento
    {
        $movimiento = Movimiento::create($nombre);
        $this->movimientoRepository->save($movimiento);
        return $movimiento;
    }
}