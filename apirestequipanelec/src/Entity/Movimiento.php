<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


class Movimiento
{

    private UuidInterface $id;
    private $nombre;
    private $materials;

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
        $this->materials = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Material[]
     */
    public function getmaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
            $material->addMovimiento($this);
        }

        return $this;
    }

    public function removeMaterial(Material $material): self
    {
        if ($this->materials->removeElement($material)) {
            $material->removeMovimiento($this);
        }

        return $this;
    }
}
