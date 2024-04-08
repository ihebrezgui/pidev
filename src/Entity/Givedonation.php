<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Givedonation
 *
 * @ORM\Table(name="givedonation")
 * @ORM\Entity
 */
class Givedonation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDonateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddonateur;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_donateur", type="string", length=255, nullable=false)
     */
    private $statutDonateur;

    /**
     * @var int
     *
     * @ORM\Column(name="Montant", type="integer", nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_de_naissance", type="date", nullable=true)
     */
    private $dateDeNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="numéro_de_téléphone", type="integer", nullable=false)
     */
    private $numéroDeTéléphone;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=false)
     */
    private $profession;

    /**
     * @var string
     *
     * @ORM\Column(name="Méthode_paiement", type="string", length=255, nullable=false)
     */
    private $méthodePaiement;

    public function getIddonateur(): ?int
    {
        return $this->iddonateur;
    }

    public function getStatutDonateur(): ?string
    {
        return $this->statutDonateur;
    }

    public function setStatutDonateur(string $statutDonateur): static
    {
        $this->statutDonateur = $statutDonateur;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $dateDeNaissance): static
    {
        $this->dateDeNaissance = $dateDeNaissance;

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

    public function getNuméroDeTéléphone(): ?int
    {
        return $this->numéroDeTéléphone;
    }

    public function setNuméroDeTéléphone(int $numéroDeTéléphone): static
    {
        $this->numéroDeTéléphone = $numéroDeTéléphone;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getMéthodePaiement(): ?string
    {
        return $this->méthodePaiement;
    }

    public function setMéthodePaiement(string $méthodePaiement): static
    {
        $this->méthodePaiement = $méthodePaiement;

        return $this;
    }


}
