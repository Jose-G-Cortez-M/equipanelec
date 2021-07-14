<?php

namespace App\Form\Model;

use App\Entity\Movimiento;
use Ramsey\Uuid\UuidInterface;

class MovimientoDto {
    
    public ?UuidInterface $id = null;
    public ?string $nombre = null;

    public static function createFromMovimiento(Movimiento $movimiento): self
    {
        $dto = new self();
        $dto->id = $movimiento->getId();
        $dto->nombre = $movimiento->getNombre();
        return $dto;
    }


    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }
}