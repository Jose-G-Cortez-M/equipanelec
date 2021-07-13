<?php

namespace App\Form\Model;

use App\Entity\Movimiento;

class MovimientoDto {
    public ?int $id = null;
    public ?string $nombre = null;

    public static function createFromMovimiento(Movimiento $movimiento): self
    {
        $dto = new self();
        $dto->id = $movimiento->getId();
        $dto->nombre = $movimiento->getNombre();
        return $dto;
    }


 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }
}