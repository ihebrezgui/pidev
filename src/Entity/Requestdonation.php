<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use DateTime;


#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\Table(name: "requestdonation")]
class Requestdonation
{
    #[ORM\Column(name: "idRequest", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $idrequest;

    #[ORM\Column(name: "formation_souhaitée", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer une formation")]
    private string $formation_souhaitee;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer un Email")]
    #[Assert\Email(message : "Email n'est pas valid")]
    private string $email;

    #[ORM\Column(name: "date_limite", type: "date", nullable: false)]
    #[Assert\NotBlank(message: "Insérer une date")]
  //  #[Assert\Callback(callback: "validateDateLimite")]
    private \DateTime $dateLimite;

    #[ORM\Column(name: "lieu_de_résidence", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer un lieu de résidence")]
    private string $lieu_de_residence;

    #[ORM\Column(name: "Description", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Insérer une description")]
    private string $description;

    public function getIdrequest()
    {
        return $this->idrequest;
    }

    public function setIdrequest(int $idrequest): void
    {
        $this->idrequest = $idrequest;
    }


    public function getFormationSouhaitee()
    {
        return $this->formation_souhaitee;
    }

    public function setFormationSouhaitee(string $formation_souhaitee)
    {
        $this->formation_souhaitee = $formation_souhaitee;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getDateLimite()
    {
        return $this->dateLimite;
    }

    public function setDateLimite(DateTimeInterface $dateLimite)
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getLieuDeResidence()
    {
        return $this->lieu_de_residence;
    }

    public function setLieuDeResidence(string $lieu_de_residence)
    {
        $this->lieu_de_residence = $lieu_de_residence;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return sprintf(
            "Requestdonation (ID: %d, Formation Souhaitée: %s, Email: %s, Date Limite: %s, Lieu de Résidence: %s)",
            $this->idrequest,
            $this->formation_souhaitee,
            $this->email,
            $this->dateLimite->format('Y-m-d'), // Format the date as YYYY-MM-DD
            $this->lieu_de_residence
        );
    }
   /*     #[Assert\Callback(callback: "validateDateLimite")]

    * public function validateDateLimite(self $context, $value) // Use `self` instead of `DateTime`
    {
        if ($value < new \DateTime('today')) {
            throw new Assert\Exception\InvalidValueException('La date limite doit être supérieure à la date actuelle.');
        }
    }*/
  /*  public function validateDateLimite(ExecutionContextInterface $context)
    {
        $currentDate = new \DateTime();

        if ($this->dateLimite <= $currentDate) {
            $context->buildViolation("La date limite doit être postérieure à la date actuelle.")
                ->atPath('dateLimite')
                ->addViolation();
        }
    }*/
}
