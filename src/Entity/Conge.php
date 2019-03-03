<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CongeRepository")
 */
class Conge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id_conge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $type;

    /**
     * @ORM\Column(type="date")
     */
    public $date_debut;

    /**
     * @ORM\Column(type="boolean")
     */
    public $debut_matin;

    /**
     * @ORM\Column(type="date")
     */
    public $date_fin;

    /**
     * @ORM\Column(type="boolean")
     */
    public $fin_matin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $statut;

    /**
     * @ORM\Column(type="integer")
     */
    public $duree;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="conges")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateur", inversedBy="conges")
     */
    private $collabo;

    const STATUS = [
        0 => 'En cours',
        1 => 'En attente chef',
        2 => 'validee chef',
        3 => 'refusee chef',
        4 => 'validee RH',
        5 => 'refusee RH',
    ];

    public function getId_conge(): ?int
    {
        return $this->id_conge;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(string $date_debut): self
    {
      $date = new \DateTime($date_debut);
      $this->date_debut = $date;
      return $this;
    }
    public function getDebut_matin(): ?bool
    {
        return $this->debut_matin;
    }

    public function setDebut_matin(bool $debut_matin): self
    {
        $this->debut_matin = $debut_matin;

        return $this;
    }

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(string $date_fin): self
    {
      $date = new \DateTime($date_fin);
      $this->date_fin = $date;
      return $this;
    }
    public function getFin_matin(): ?bool
    {
        return $this->fin_matin;
    }

    public function setFin_matin(bool $fin_matin): self
    {
        $this->fin_matin = $fin_matin;

        return $this;
    }
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getCollabo(): ?Collaborateur
    {
        return $this->collabo;
    }

    public function setCollabo(?Collaborateur $collabo): self
    {
        $this->collabo = $collabo;

        return $this;
    }
}
