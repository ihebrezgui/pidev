<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity
 */
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="idQuiz", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idquiz;

    /**
     * @var string|null
     *
     * @ORM\Column(name="typeF", type="string", length=255, nullable=true)
     */
    private $typef;

    /**
     * @var string|null
     *
     * @ORM\Column(name="questions", type="string", length=255, nullable=true)
     */
    private $questions;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer_1", type="string", length=255, nullable=true)
     */
    private $answer1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer_2", type="string", length=255, nullable=true)
     */
    private $answer2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer_3", type="string", length=255, nullable=true)
     */
    private $answer3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="correct_answer", type="string", length=100, nullable=true)
     */
    private $correctAnswer;

    /**
     * @var int
     *
     * @ORM\Column(name="idFormation", type="integer", nullable=false)
     */
    private $idformation;

    public function getIdquiz(): ?int
    {
        return $this->idquiz;
    }

    public function getTypef(): ?string
    {
        return $this->typef;
    }

    public function setTypef(?string $typef): static
    {
        $this->typef = $typef;

        return $this;
    }

    public function getQuestions(): ?string
    {
        return $this->questions;
    }

    public function setQuestions(?string $questions): static
    {
        $this->questions = $questions;

        return $this;
    }

    public function getAnswer1(): ?string
    {
        return $this->answer1;
    }

    public function setAnswer1(?string $answer1): static
    {
        $this->answer1 = $answer1;

        return $this;
    }

    public function getAnswer2(): ?string
    {
        return $this->answer2;
    }

    public function setAnswer2(?string $answer2): static
    {
        $this->answer2 = $answer2;

        return $this;
    }

    public function getAnswer3(): ?string
    {
        return $this->answer3;
    }

    public function setAnswer3(?string $answer3): static
    {
        $this->answer3 = $answer3;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(?string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getIdformation(): ?int
    {
        return $this->idformation;
    }

    public function setIdformation(int $idformation): static
    {
        $this->idformation = $idformation;

        return $this;
    }


}
