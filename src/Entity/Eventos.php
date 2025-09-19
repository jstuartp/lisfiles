<?php

namespace App\Entity;

use App\Repository\EventosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventosRepository::class)]
class Eventos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $idEvento = null;

    #[ORM\Column]
    private ?\DateTime $fechaEvento = null;

    #[ORM\Column]
    private ?float $latitudEvento = null;

    #[ORM\Column]
    private ?float $longitudEvento = null;

    #[ORM\Column]
    private ?float $magnitudEvento = null;

    #[ORM\Column(nullable: true)]
    private ?float $aceleracionEvento = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lugarAceleracion = null;

    #[ORM\Column(nullable: true)]
    private ?float $profunidadEvento = null;

    #[ORM\Column(nullable: true)]
    private ?int $informe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdEvento(): ?string
    {
        return $this->idEvento;
    }

    public function setIdEvento(string $idEvento): static
    {
        $this->idEvento = $idEvento;

        return $this;
    }

    public function getFechaEvento(): ?\DateTime
    {
        return $this->fechaEvento;
    }

    public function setFechaEvento(\DateTime $fechaEvento): static
    {
        $this->fechaEvento = $fechaEvento;

        return $this;
    }

    public function getLatitudEvento(): ?float
    {
        return $this->latitudEvento;
    }

    public function setLatitudEvento(float $latitudEvento): static
    {
        $this->latitudEvento = $latitudEvento;

        return $this;
    }

    public function getLongitudEvento(): ?float
    {
        return $this->longitudEvento;
    }

    public function setLongitudEvento(float $longitudEvento): static
    {
        $this->longitudEvento = $longitudEvento;

        return $this;
    }

    public function getMagnitudEvento(): ?float
    {
        return $this->magnitudEvento;
    }

    public function setMagnitudEvento(float $magnitudEvento): static
    {
        $this->magnitudEvento = $magnitudEvento;

        return $this;
    }

    public function getAceleracionEvento(): ?float
    {
        return $this->aceleracionEvento;
    }

    public function setAceleracionEvento(?float $aceleracionEvento): static
    {
        $this->aceleracionEvento = $aceleracionEvento;

        return $this;
    }

    public function getLugarAceleracion(): ?string
    {
        return $this->lugarAceleracion;
    }

    public function setLugarAceleracion(?string $lugarAceleracion): static
    {
        $this->lugarAceleracion = $lugarAceleracion;

        return $this;
    }

    public function getProfunidadEvento(): ?float
    {
        return $this->profunidadEvento;
    }

    public function setProfunidadEvento(?float $profunidadEvento): static
    {
        $this->profunidadEvento = $profunidadEvento;

        return $this;
    }

    public function getInforme(): ?int
    {
        return $this->informe;
    }

    public function setInforme(?int $informe): static
    {
        $this->informe = $informe;

        return $this;
    }
}
