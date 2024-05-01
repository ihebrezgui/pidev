<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Cours; 
use App\Entity\Quiz;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: 'formation')]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idFormation', type: 'integer')]
    private int $idFormation;

    #[ORM\Column(name: 'typeF', type: 'string', length: 100)]
    #[Assert\NotBlank(message: "Le champ typeF ne doit pas être vide.")]
    #[Assert\Choice(choices: ['Devops', 'Mobile', 'GL', 'Santé', 'Math et logique', 'Développement Personnel', 'Data Science', 'Culture générale', 'Finance'], message: "Veuillez sélectionner un type de formation valide.")]
    private string $typeF;


    #[ORM\Column(name: 'img', type: 'string', length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(name: 'prix', type: 'float')]
    #[Assert\NotBlank(message: "Le champ prix ne doit pas être vide.")]
    #[Assert\Positive(message: "Le prix doit être positif.")]
    private float $prix;

    #[ORM\Column(name: 'duree', type: 'string', length: 100)]
    #[Assert\NotBlank(message: "Le champ durée ne doit pas être vide.")]
    #[Assert\PositiveOrZero(message: "La durée doit être positive ou zéro.")]
    private string $duree;

    #[ORM\Column(name: 'status', type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le champ status ne doit pas être vide.")]
    #[Assert\Choice(choices: ['actif', 'inactif', 'en attente'], message: "Le status doit être 'actif', 'inactif' ou 'en attente'.")]
    private ?string $status;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Cours::class)]
    private Collection $courses;
    
    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: 'Quiz')]
    private Collection $quizzes;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
    }

    public function getIdFormation(): int
    {
        return $this->idFormation;
    }

    public function getTypeF(): ?string
{
    return $this->typeF;
}


    public function setTypeF(string $typeF): self
    {
        $this->typeF = $typeF;

        return $this;
    }

    public function setImg(?string $img): self {
        $this->img = $img;
        return $this;
    }
    
public function getImg(): ?string
{
    return $this->img;
}


    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDuree(): string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Cours $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setIdFormation($this);
        }
    
        return $this;
    }    

    public function removeCourse(Cours $course): self
    {
        if ($this->courses->removeElement($course)) 
        return $this;
    }
    

    public function __toString() {
        return $this->typeF;
    }
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->setFormation($this);
        }

        return $this;
    }
 public function hasQuiz(): bool
{
    return!empty($this->quizzes);
}
    


}
