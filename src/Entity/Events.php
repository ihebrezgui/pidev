<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventsRepository;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[ORM\Table(name: "events")]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id_event;

    #[ORM\Column(type: "string", length: 50, nullable: false)]
    private $nom;

    #[ORM\Column(type: "date", nullable: false)]
    #[Assert\NotBlank(message: "Date cannot be blank")]
    #[Assert\Type("\DateTime", message: "Date must be a valid date")]
    #[Assert\GreaterThan("2023-12-31", message: "Date must start from 2024")]
    private $dateEvent;


    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Number of places cannot be blank")]
    #[Assert\Regex(pattern: "/^\d{1,3}$/", message: "Number of places must be digits with a maximum of 3 digits")]
    private $nbrPlace;


    #[ORM\Column(type: "string", length: 200, nullable: false)]
    #[Assert\NotBlank(message: "Description cannot be blank")]
    #[Assert\Length(max: 200, maxMessage: "Description must be less than {{ limit }} characters")]
    private $description;

    public function getId_event(): ?int
    {
        return $this->id_event;
    }
    

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function __toString(): string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDateEvent(): ?DateTime
    {
        return $this->dateEvent;
    }

    public function setDateEvent(DateTime $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getNbrPlace(): ?int
    {
        return $this->nbrPlace;
    }

    public function setNbrPlace(?int $nbrPlace): self
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}