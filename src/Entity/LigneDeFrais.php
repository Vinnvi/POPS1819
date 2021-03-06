<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LigneDeFraisRepository")
 */
class LigneDeFrais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $intitule;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $mission;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutValidation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $avance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NoteDeFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Projet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Projet;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $justificatif;

    public function __construct()
    {
      $this->statutValidation = "Non validée";
      $this->avance = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatutValidation(): ?string
    {
        return $this->statutValidation;
    }

    public function setStatutValidation(string $statutValidation): self
    {
        $this->statutValidation = $statutValidation;

        return $this;
    }

    public function getAvance(): ?bool
    {
        return $this->avance;
    }

    public function setAvance(bool $avance): self
    {
        $this->avance = $avance;

        return $this;
    }

    public function getNote(): ?NoteDeFrais
    {
        return $this->Note;
    }

    public function setNote(?NoteDeFrais $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->Projet;
    }

    public function setProjet(?Projet $Projet): self
    {
        $this->Projet = $Projet;

        return $this;
    }

    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(?string $justificatif): self
    {
        $this->justificatif = $justificatif;

        return $this;
    }
}
