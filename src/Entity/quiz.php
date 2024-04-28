<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "quiz")]
#[ORM\Entity]
class Quiz
{
    #[ORM\Column(name: "idQuiz", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $idquiz;

    #[ORM\Column(name: "typeF", type: "string", length: 255, nullable: false)]
    private $typef;

    #[ORM\Column(name: "questions", type: "string", length: 255, nullable: false)]
    private $questions;

    #[ORM\Column(name: "answer_1", type: "string", length: 255, nullable: false)]
    private $answer1;

    #[ORM\Column(name: "answer_2", type: "string", length: 255, nullable: false)]
    private $answer2;

    #[ORM\Column(name: "answer_3", type: "string", length: 255, nullable: false)]
    private $answer3;

    #[ORM\Column(name: "correct_answer", type: "string", length: 255, nullable: false)]
    private $correctAnswer;

    #[ORM\Column(name: "idFormation", type: "integer", nullable: false)]
    private $idFormation;

    public function getIdquiz(): ?int
    {
        return $this->idquiz;
    }

    public function getTypef(): ?string
    {
        return $this->typef;
    }

    public function setTypef(string $typef): static
    {
        $this->typef = $typef;

        return $this;
    }

    public function getQuestions(): ?string
    {
        return $this->questions;
    }

    public function setQuestions(string $questions): static
    {
        $this->questions = $questions;

        return $this;
    }

    public function getAnswer1(): ?string
    {
        return $this->answer1;
    }

    public function setAnswer1(string $answer1): static
    {
        $this->answer1 = $answer1;

        return $this;
    }

    public function getAnswer2(): ?string
    {
        return $this->answer2;
    }

    public function setAnswer2(string $answer2): static
    {
        $this->answer2 = $answer2;

        return $this;
    }

    public function getAnswer3(): ?string
    {
        return $this->answer3;
    }

    public function setAnswer3(string $answer3): static
    {
        $this->answer3 = $answer3;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getIdformation(): ?int
    {
        return $this->idFormation;
    }

    public function setIdformation(int $idformation): static
    {
        $this->idFormation = $idformation;

        return $this;
    }
}
