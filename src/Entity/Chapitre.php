<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapitre
 *
 * @ORM\Table(name="chapitre", indexes={@ORM\Index(name="pk_id2", columns={"idCours"})})
 * @ORM\Entity
 */
class Chapitre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idChapitre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idchapitre;

    /**
     * @var string
     *
     * @ORM\Column(name="nomC", type="string", length=100, nullable=false)
     */
    private $nomc;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var \Cours
     *
     * @ORM\ManyToOne(targetEntity="Cours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCours", referencedColumnName="idCours")
     * })
     */
    private $idcours;

    public function getIdchapitre(): ?int
    {
        return $this->idchapitre;
    }

    public function getNomc(): ?string
    {
        return $this->nomc;
    }

    public function setNomc(string $nomc): static
    {
        $this->nomc = $nomc;

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

   /* public function getIdcours(): ?Cours
    {
        return $this->idcours;
    }*/

    public function setIdcours(?Cours $idcours): static
    {
        $this->idcours = $idcours;

        return $this;
    }

    public function getIdcours(): ?Cours
    {
        return $this->idcours;
    }


}
