<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dons
 *
 * @ORM\Table(name="dons", indexes={@ORM\Index(name="cle", columns={"idDonateur"})})
 * @ORM\Entity
 */
class Dons
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDon", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddon;

    /**
     * @var int
     *
     * @ORM\Column(name="somme", type="integer", nullable=false)
     */
    private $somme;

    /**
     * @var int
     *
     * @ORM\Column(name="idDonateur", type="integer", nullable=false)
     */
    private $iddonateur;

    public function getIddon(): ?int
    {
        return $this->iddon;
    }

    public function getSomme(): ?int
    {
        return $this->somme;
    }

    public function setSomme(int $somme): static
    {
        $this->somme = $somme;

        return $this;
    }

    public function getIddonateur(): ?int
    {
        return $this->iddonateur;
    }

    public function setIddonateur(int $iddonateur): static
    {
        $this->iddonateur = $iddonateur;

        return $this;
    }


}
