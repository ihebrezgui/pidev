<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChapitreRepository;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
#[ORM\Table(name: 'chapitre', indexes: [
    new ORM\Index(name: 'pk_id2', columns: ['idCours']),
])]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idChapitre', type: 'integer', nullable: false)]
    private int $idchapitre;

    #[ORM\Column(name: 'nomC', type: 'string', length: 100, nullable: false)]
    private string $nomc;

    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    private string $description;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idCours', referencedColumnName: 'idCours')]
    private Cours $idcours;

    public function getIdchapitre(): int
    {
        return $this->idchapitre;
    }

    public function setIdchapitre(int $idchapitre): self
    {
        $this->idchapitre = $idchapitre;

        return $this;
    }

    public function getNomc(): string
    {
        return $this->nomc;
    }

    public function setNomc(string $nomc): self
    {
        $this->nomc = $nomc;

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

    public function getIdcours(): Cours
    {
        return $this->idcours;
    }

    public function setIdcours(Cours $idcours): self
    {
        $this->idcours = $idcours;

        return $this;
    }
}