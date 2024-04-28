<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CoursRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[ORM\Table(name: 'cours', indexes: [new ORM\Index(name: 'pk_id', columns: ['idFormation'])])]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCours', type: 'integer', nullable: false)]
    private int $idcours;

    #[ORM\Column(name: 'nomCours', type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message: "Le nom du cours ne doit pas être vide.")]
    #[Assert\Length(max: 100, maxMessage: "Le nom du cours ne doit pas dépasser 100 caractères.")]
    private string $nomcours;

    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message: "La description ne doit pas être vide.")]
    #[Assert\Length(max: 100, maxMessage: "La description ne doit pas dépasser 100 caractères.")]
    private string $description;

    #[ORM\Column(name: 'categorie', type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message: "La catégorie ne doit pas être vide.")]
    #[Assert\Length(max: 100, maxMessage: "La catégorie ne doit pas dépasser 100 caractères.")]
    private string $categorie;

    #[ORM\Column(name: 'cours', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le champ cours ne doit pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le champ cours ne doit pas dépasser 255 caractères.")]
    private string $cours;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFormation', referencedColumnName: 'idFormation', nullable: false)]
    private Formation $formation;

    public function getIdcours(): int
    {
        return $this->idcours;
    }

    public function setIdcours(int $idcours): self
    {
        $this->idcours = $idcours;

        return $this;
    }

    public function getNomcours(): string
    {
        return $this->nomcours;
    }

    public function setNomcours(string $nomcours): self
    {
        $this->nomcours = $nomcours;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCours(): string
    {
        return $this->cours;
    }

    public function setCours(string $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function getIdformation(): Formation
    {
        return $this->formation;
    }

    public function setIdformation(Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}