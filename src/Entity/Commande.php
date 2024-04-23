<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="idc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idc;

    /**
     * @var int
     *
     * @ORM\Column(name="tel", type="integer", nullable=false)
     * @Assert\NotBlank(message="le champs tel ne doit pas etre vide")
     * @Assert\Positive(message="Le numéro de téléphone doit être positif.")
     * @Assert\Regex(
     *     pattern="/^\d{8}$/",
     *     message="Le numéro de téléphone doit être composé de 8 chiffres."
     * )
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]+$/",
     *     message="Le nom doit contenir uniquement des lettres."
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]+$/",
     *     message="Le nom doit contenir uniquement des lettres."
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Email(message="L'adresse e-mail n'est pas valide.")
     */
    private $mail;

    /**
     * @var array
     *
     * @ORM\Column(name="panier", type="json", nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     */
    private $panier;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     */
    private $address;

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPanier(): ?array
    {
        return $this->panier;
    }

    public function setPanier(?array $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
