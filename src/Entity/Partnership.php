<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PartnershipRepository;


 #[ORM\Entity(repositoryClass: PartnershipRepository::class)]

class Partnership
{
    
     #[ORM\Column(name:"idP", type:"integer", nullable:false)]
     #[ORM\Id]
     #[ORM\GeneratedValue(strategy:"IDENTITY")]
     
    private $idp;

    
    #[ORM\Column(name:"nom_p", type:"string", length:30, nullable:false)]
     
    private $nomP;

  
     #[ORM\Column(name:"domaine", type:"string", length:50, nullable:false)]
     
    private $domaine;

    
     #[ORM\Column(name:"choix", type:"integer", nullable:false)]
     
    private $choix;

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): self
    {
        $this->nomP = $nomP;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getChoix(): ?int
    {
        return $this->choix;
    }

    public function setChoix(int $choix): self
    {
        $this->choix = $choix;

        return $this;
    }


}
