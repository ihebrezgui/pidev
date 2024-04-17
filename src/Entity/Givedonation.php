<?php

namespace App\Entity;
use App\Repository\GiveRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GiveRepository :: class)]


#[ORM\Table(name: "givedonation")]
class Givedonation
{
    #[ORM\Column(name: "idDonateur", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $iddonateur;

    #[ORM\Column(name: "statut_donateur", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message : "Insérer votre statut")]
    private string $statutDonateur;

    #[ORM\Column(name: "Montant", type: "decimal",  nullable: false)]
    #[Assert\NotBlank(message: "Insérer un montant")]
    #[Assert\Positive(message :"Insérer un montant valide")]
    private string $montant;

    #[ORM\Column(name: "date_de_naissance", type: "date", nullable: true)]
    #[Assert\NotBlank(message: "Insérer une date")]
    private ?\DateTime $dateDeNaissance;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message : " Insérer un email")]
    #[Assert\Email(message : "Email n'est pas valid")]
    private string $email;

    #[ORM\Column(name: "numéro_de_téléphone", type: "string", length: 255, nullable: false)]  // Change type to string for phone numbers
    #[Assert\NotBlank(message: "Insérer un numéro de téléphone")]
    #[Assert\Length(min: 10, max: 15,maxMessage: "votre numéro de téléphone n'est pas valide ")]
    private string $numeroDeTelephone;

    #[ORM\Column(name: "profession", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer une profession")]
    private string $profession;

    #[ORM\Column(name: "Méthode_paiement", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer une méthode de paiement")]
    private string $methodePaiement;

    #[ORM\ManyToOne(targetEntity: "Requestdonation")]
    #[ORM\JoinColumn(name: "idRequest", referencedColumnName: "idRequest")]
    private Requestdonation $idrequest;
    public function getIddonateur()
    {
        return $this->iddonateur;
    }

    public function getStatutDonateur()
    {
        return $this->statutDonateur;
    }

    public function setStatutDonateur(string $statutDonateur)
    {
        $this->statutDonateur = $statutDonateur;

        return $this;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant(int $montant)
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTime $dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getNumeroDeTelephone()
    {
        return $this->numeroDeTelephone;
    }

    public function setNumeroDeTelephone(int $numeroDeTelephone)
    {
        $this->numeroDeTelephone = $numeroDeTelephone;

        return $this;
    }

    public function getProfession()
    {
        return $this->profession;
    }

    public function setProfession(string $profession)
    {
        $this->profession = $profession;

        return $this;
    }

    public function getMethodePaiement()
    {
        return $this->methodePaiement;
    }

    public function setMethodePaiement(string $methodePaiement)
    {
        $this->methodePaiement = $methodePaiement;

        return $this;
    }

    public function getIdrequest()
    {
        return $this->idrequest;
    }

    public function setIdrequest(Requestdonation $idrequest)
    {
        $this->idrequest = $idrequest;

        return $this;
    }


}
