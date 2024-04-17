<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateur")]
class Utilisateur implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'The name cannot be blank')]
    #[Assert\Length(min: 3, minMessage: 'Le nom  doit comporter au moins {{ 3}} caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'The name must contain only alphabetic characters'
    )]
    private $nom;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Le nom de famille ne peut pas être vide')]
    #[Assert\Length(min: 3, minMessage: 'Le nom  doit comporter au moins {{ 3}} caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le nom ne doit contenir que des caractères alphabétiques.
        '
    )]
    private $prenom;

    #[ORM\Column(type: "date", nullable: false)]
    private $dateNais;

    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Ce champ ne doit pas être vide.")]    
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: 'Le numéro de téléphone doit contenir 8 chiffres
        '
    )]
    private $numTel;

    #[ORM\Column(type: "string", length: 50, nullable: false)]
    private $email;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private $sexe;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private $role;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/',
        message: 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre, un caractère spécial et au moins 8 caractères.'
    )]
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

    public function getUsername(): string
    {
        return $this->email;
    }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword(): string
    {
        return $this->password ?? '';;
    }

    public function getSalt()
    {
        // You can ignore this method if you're not using bcrypt or another encryption method that requires a salt
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
