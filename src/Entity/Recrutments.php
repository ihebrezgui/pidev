<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecrutmentsRepository;


 
 #[ORM\Entity(repositoryClass:RecrutmentsRepository::class)]
class Recrutments
{
     #[ORM\Column(name:"idR", type:"integer", nullable:false)]
     #[ORM\Id]
     #[ORM\GeneratedValue(strategy:"IDENTITY")]
     
    private $idr;

    #[ORM\Column(name:"poste", type:"string", length:50, nullable:false)]
    
    private $poste;

    #[ORM\Column(name:"discription", type:"string", length:1000, nullable:false)]
     
    private $discription;

    #[ORM\Column(name:"salaire", type:"float", precision:10, scale:0, nullable:false)]
     
    private $salaire;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(string $discription): self
    {
        $this->discription = $discription;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }


}
