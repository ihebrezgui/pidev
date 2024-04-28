<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationRepository;

#[ORM\Table(name: "formation")]
#[ORM\Entity]
class Formation
{
    #[ORM\Column(name: "idFormation", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $idFormation;

    #[ORM\Column(name: "typeF", type: "string", length: 100, nullable: false)]
    private string $typef;

    #[ORM\Column(name: "Img", type: "string", length: 100, nullable: false)]
    private string $img;


    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $prix;

    #[ORM\Column(name: "duree", type: "string", length: 100, nullable: false)]
    private string $duree;

    #[ORM\Column(name: "status", type: "string", length: 100, nullable: false, options: ["default" => "en attente"])]
    private string $status = 'en attente';

    public function getIdformation(): ?int
    {
        return $this->idformation;
    }

    public function getTypef(): ?string
    {
        return $this->typef;
    }

    public function setTypef(string $typef): static
    {
        $this->typef = $typef;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
    public function __toString(): string
    {
        return "Formation {idFormation: $this->idFormation, typef: $this->typef, img: $this->img, prix: $this->prix, duree: $this->duree, status: $this->status}";
    }

}
