<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CodePromoRepository;

#[ORM\Entity(repositoryClass: CodePromoRepository::class)]
class CodePromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $idPromo;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $code;

    #[ORM\Column(type: "date", nullable: false)]
    private $dateExpiration;

    #[ORM\Column(type: "integer", nullable: false)]
    private $active;

    #[ORM\Column(type: "integer", nullable: false, name: "idUser")]
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
