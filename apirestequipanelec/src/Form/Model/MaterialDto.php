<?php

namespace App\Form\Model;

use App\Entity\Material;

class MaterialDto {
    public ?string $nombre = null;
    public ?string $base64Imagen = null;
    /** @var \App\Form\Model\MovimientoDto[]|null */
    public ?array $movimientos = [];

    public function __construct()
    {
        $this->movimientos = [];
    }

    public static function createFromMaterial(Material $material): self
    {
        $dto = new self();
        $dto->nombre = $material->getNombre();
        return $dto;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }


    public function getBase64Imagen(): ?string
    {
        return $this->base64Imagen;
    }

    /** 
     * @return \App\Form\Model\MovimientoDto[]|null 
     */

    public function getMovimientos(): ?array
    {
        return $this->movimientos;
    }
}