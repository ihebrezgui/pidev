<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Requestdonation
 *
 * @ORM\Table(name="requestdonation")
 * @ORM\Entity
 */
class Requestdonation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRequest", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrequest;

    /**
     * @var string
     *
     * @ORM\Column(name="formation_souhaitée", type="string", length=255, nullable=false)
     */
    private $formationSouhaitée;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limite", type="date", nullable=false)
     */
    private $dateLimite;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_de_résidence", type="string", length=255, nullable=false)
     */
    private $lieuDeRésidence;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=false)
     */
    private $description;

    public function getIdrequest(): ?int
    {
        return $this->idrequest;
    }

    public function getFormationSouhaitée(): ?string
    {
        return $this->formationSouhaitée;
    }

    public function setFormationSouhaitée(string $formationSouhaitée): static
    {
        $this->formationSouhaitée = $formationSouhaitée;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeInterface $dateLimite): static
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getLieuDeRésidence(): ?string
    {
        return $this->lieuDeRésidence;
    }

    public function setLieuDeRésidence(string $lieuDeRésidence): static
    {
        $this->lieuDeRésidence = $lieuDeRésidence;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


}
