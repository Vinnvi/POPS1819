<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{

    const STATUT = [
        0 => 'OK',
        1 => 'EN_ATTENTE',
        2 => 'ACTION_NECESSAIRE',
        3 => 'REFUS',
    ];

    const CATEGORIE = [
        0 => 'Note de frais',
        1 => 'D. de congés',
        2 => 'D. d\'information',
        3 => 'Mission',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateur", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $collaborateur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $personnel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cumulable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCollaborateur(): ?Collaborateur
    {
        return $this->collaborateur;
    }

    public function setCollaborateur(?Collaborateur $collaborateur): self
    {
        $this->collaborateur = $collaborateur;

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

    public function getPersonnel(): ?bool
    {
        return $this->personnel;
    }

    public function setPersonnel(bool $personnel): self
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getCumulable(): ?bool
    {
        return $this->cumulable;
    }

    public function setCumulable(bool $cumulable): self
    {
        $this->cumulable = $cumulable;

        return $this;
    }

    public function getVu(): ?bool
    {
        return $this->vu;
    }

    public function setVu(bool $vu): self
    {
        $this->vu = $vu;

        return $this;
    }
}
