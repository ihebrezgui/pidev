<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "user")]
#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "userId", type: "integer", nullable: false)]
    private $userid;

    #[ORM\Column(name: "date_nais", type: "date", nullable: true)]
    private $dateNais;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: true)]
    private $email;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: true)]
    private $nom;

    #[ORM\Column(name: "num_tel", type: "integer", nullable: false)]
    private $numTel;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: true)]
    private $password;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: true)]
    private $prenom;

    #[ORM\Column(name: "role", type: "boolean", nullable: true)]
    private $role;

    #[ORM\Column(name: "sexe", type: "string", length: 255, nullable: true)]
    private $sexe;

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(?\DateTimeInterface $dateNais): static
    {
        $this->dateNais = $dateNais;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): static
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function isRole(): ?bool
    {
        return $this->role;
    }

    public function setRole(?bool $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;
        return $this;
    }
}
