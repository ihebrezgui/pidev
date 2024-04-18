<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CodePromoRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CodePromoRepository::class)]
class CodePromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $idPromo;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    #[Assert\Regex(
        pattern: '/^\d{7}$/',
        message: 'Le code doit contenir 8 chiffres
        '
    )]    #[Assert\NotBlank(message: "Le code est obligatoire")]
    private $code;

    #[ORM\Column(type: "date", nullable: false)]
    #[Assert\NotBlank(message: "La date d'expiration est requise")]
    #[Assert\Type("\DateTime", message: "La date d'expiration doit être une date valide")]
    #[Assert\GreaterThan("today", message: "La date d'expiration doit se situer dans le futur")]
    private $dateExpiration;

    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Le statut actif est requis")]
    #[Assert\Choice(choices: [0, 1], message: "L'état actif doit être 0 ou 1")]
    private $active;

    #[ORM\Column(type: "integer", nullable: false, name: "idUser")]
    #[Assert\NotBlank(message: "L'identifiant de l'utilisateur est requis")]
    private $iduser;

    public function getIdPromo(): ?int
    {
        return $this->idPromo;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDateExpiration(): ?DateTime
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(DateTime $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;
        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->iduser;
    }

    public function setIdUser(int $iduser): self
    {
        $this->iduser = $iduser;
        return $this;
    }
}
