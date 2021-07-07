<?php

namespace App\Form\Model;

use App\Entity\Movimiento;

class MovimientoDto {
    public $id;
    public $nombre;

    public static function createFromMovimiento(Movimiento $movimiento): self
    {
        $dto = new self();
        $dto->id = $movimiento->getId();
        $dto->name = $movimiento->getNombre();
        return $dto;
    }

}