<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Facturation
 *
 * @ORM\Table(name="facturation", indexes={@ORM\Index(name="pk-id", columns={"IdPanier"})})
 * @ORM\Entity
 */
class Facturation
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdFacture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfacture;

    /**
     * @var string
     *
     * @ORM\Column(name="infoClient", type="string", length=100, nullable=false)
     */
    private $infoclient;

    /**
     * @var int
     *
     * @ORM\Column(name="Quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateFacturation", type="date", nullable=false)
     */
    private $datefacturation;

    /**
     * @var int
     *
     * @ORM\Column(name="IdPanier", type="integer", nullable=false)
     */
    private $idpanier;

    public function getIdfacture(): ?int
    {
        return $this->idfacture;
    }

    public function getInfoclient(): ?string
    {
        return $this->infoclient;
    }

    public function setInfoclient(string $infoclient): static
    {
        $this->infoclient = $infoclient;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatefacturation(): ?\DateTimeInterface
    {
        return $this->datefacturation;
    }

    public function setDatefacturation(\DateTimeInterface $datefacturation): static
    {
        $this->datefacturation = $datefacturation;

        return $this;
    }

    public function getIdpanier(): ?int
    {
        return $this->idpanier;
    }

    public function setIdpanier(int $idpanier): static
    {
        $this->idpanier = $idpanier;

        return $this;
    }


}
