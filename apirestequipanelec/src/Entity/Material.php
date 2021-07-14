<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Material
{
    private UuidInterface $id;
    private $nombre;
    private $imagen;
    private $movimientos;

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
        $this->movimientos = new ArrayCollection();
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public function getId(): UuidInterface
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

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection|Movimiento[]
     */
    public function getMovimientos(): Collection
    {
        return $this->movimientos;
    }

    public function addMovimiento(Movimiento $movimiento): self
    {
        if (!$this->movimientos->contains($movimiento)) {
            $this->movimientos[] = $movimiento;
        }

        return $this;
    }

    public function removeMovimiento(Movimiento $movimiento): self
    {

        if ($this->movimientos->contains($movimiento)) {
            $this->movimientos->removeElement($movimiento);
        }
        return $this;
    }
}
