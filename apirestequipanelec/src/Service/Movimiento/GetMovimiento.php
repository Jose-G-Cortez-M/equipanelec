<?php

namespace App\Service\Movimiento;

use App\Entity\Movimiento;
use App\Model\Exception\Movimiento\MovimientoNotFound;
use App\Repository\MovimientoRepository;
use Ramsey\Uuid\Uuid;

class GetMovimiento
{

    private MovimientoRepository $movimientoRepository;

    public function __construct(MovimientoRepository $movimientoRepository)
    {
        $this->movimientoRepository = $movimientoRepository;
    }

    public function __invoke(string $id): ?Movimiento
    {
        $movimiento = $this->movimientoRepository->find(Uuid::fromString($id));
        if (!$movimiento) {
            MovimientoNotFound::throwException();
        }
        return $movimiento;
    }
}