<?php

namespace App\Form\Model;

use App\Entity\Material;

class MaterialDto {
    public $nombre;
    public $base64Imagen;
    public $movimientos;

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
}