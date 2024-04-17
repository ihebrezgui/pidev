<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanningRepository;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
#[ORM\Table(name: "planning")]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_planning", type: "integer", nullable: false)]
    private int $idPlanning;

    #[ORM\Column(name: "titre", type: "string", length: 255, nullable: false)]
    private string $titre;


    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTimeInterface $date;

    #[ORM\Column(name: "approved", type: "boolean", nullable: false)]
    private bool $approved;

    #[ORM\ManyToOne(targetEntity: Events::class)]
    #[ORM\JoinColumn(name: "id_event", referencedColumnName: "id_event")]
    private ?Events $idEvent;

    public function getIdPlanning(): ?int
    {
        return $this->idPlanning;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }




    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;
        return $this;
    }

    public function getIdEvent(): ?Events
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Events $idEvent): self
    {
        $this->idEvent = $idEvent;
        return $this;
    }
}
