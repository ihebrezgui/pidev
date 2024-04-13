<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserTrait;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateur")]
class Utilisateur 
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $prenom;

    #[ORM\Column(type: "date", nullable: false)]
    private $dateNais;

    #[ORM\Column(type: "integer", nullable: false)]
    private $numTel;

    #[ORM\Column(type: "string", length: 50, nullable: false)]
    private $email;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private $sexe;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private $role;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $password;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $resttoken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNais(): ?DateTime
    {
        return $this->dateNais;
    }

    public function setDateNais(DateTime $dateNais): self
    {
        $this->dateNais = $dateNais;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(?int $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getResttoken(): ?string
    {
        return $this->resttoken;
    }

    public function setResttoken(?string $resttoken): self
    {
        $this->resttoken = $resttoken;

        return $this;
    }


}
