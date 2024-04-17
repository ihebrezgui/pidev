<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EnseignentRepository;


 
 #[ORM\Entity(repositoryClass: EnseignentRepository::class)]

class Enseignent
{
   
     #[ORM\Column(name:"idE", type:"integer", nullable:false)]
     #[ORM\Id]
     #[ORM\GeneratedValue(strategy:"IDENTITY")]
    
    private $ide;

     #[ORM\Column(name:"nomE", type:"string", length:20, nullable:false)]
     
    private $nome;

  
     #[ORM\Column(name:"prenomE", type:"string", length:20, nullable:false)]
    
    private $prenome;

    
     #[ORM\Column(name:"email", type:"text", length:65535, nullable:false)]
     
    private $email;

    
    #[ORM\Column(name:"matier", type:"string", length:50, nullable:false)]
     
    private $matier;

    
     #[ORM\Column(name:"comptence", type:"text", length:65535, nullable:false)]
     
    private $comptence;

    
     #[ORM\Column(name:"ageE", type:"integer", nullable:false)]
     
    private $agee;

    
     #[ORM\Column(name:"langue", type:"string", length:30, nullable:false)]
     
    private $langue;

    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getPrenome(): ?string
    {
        return $this->prenome;
    }

    public function setPrenome(string $prenome): self
    {
        $this->prenome = $prenome;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMatier(): ?string
    {
        return $this->matier;
    }

    public function setMatier(string $matier): self
    {
        $this->matier = $matier;

        return $this;
    }

    public function getComptence(): ?string
    {
        return $this->comptence;
    }

    public function setComptence(string $comptence): self
    {
        $this->comptence = $comptence;

        return $this;
    }

    public function getAgee(): ?int
    {
        return $this->agee;
    }

    public function setAgee(int $agee): self
    {
        $this->agee = $agee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }


}
