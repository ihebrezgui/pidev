<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donateur
 *
 * @ORM\Table(name="donateur")
 * @ORM\Entity
 */
class Donateur
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
     * @var int
     *
     * @ORM\Column(name="nom", type="integer", nullable=false)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="Prenom", type="integer", nullable=false)
     */
    private $prenom;

    public function getIddonateur(): ?int
    {
        return $this->iddonateur;
    }

    public function getNom(): ?int
    {
        return $this->nom;
    }

    public function setNom(int $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?int
    {
        return $this->prenom;
    }

    public function setPrenom(int $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }


}
