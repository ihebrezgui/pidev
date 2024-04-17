<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidatRepository;


 
 #[ORM\Entity(repositoryClass: CandidatRepository::class)]
 
class Candidat
{
     #[ORM\Column(name:"nom", type:"string", length:11, nullable:false)]
    
    private $nom;

    #[ORM\Column(name:"prenom", type:"string", length:11, nullable:false)]
     
    private $prenom;

    #[ORM\Column(name:"lettre", type:"string", length:3000, nullable:false)]
     
    private $lettre;

    #[ORM\Column(name:"idRecrutment", type:"integer", nullable:false)]
     
    private $idrecrutment;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ORM\OneToOne(targetEntity: Recrutments::class, inversedBy: 'candidat', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private  $recrutments = null;
    private $idc;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLettre(): ?string
    {
        return $this->lettre;
    }

    public function setLettre(string $lettre): self
    {
        $this->lettre = $lettre;

        return $this;
    }

    public function getIdrecrutment(): ?int
    {
        return $this->idrecrutment;
    }

    public function setIdrecrutment(int $idrecrutment): self
    {
        $this->idrecrutment = $idrecrutment;

        return $this;
    }

    public function getIdc(): ?Recrutments
    {
        return $this->idc;
    }

    public function setIdc(?Recrutments $idc): self
    {
        $this->idc = $idc;

        return $this;
    }


}
