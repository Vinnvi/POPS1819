<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="date")
     */
    private $DateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $DateFin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Collaborateur")
     */
    private $collabos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Service;

    const STATUS = [
        0 => 'A venir',
        1 => 'En cours',
        2 => 'Suspendu',
        3 => 'Fini',
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->collabos = new ArrayCollection();
        $this->setStatus(Projet::STATUS[0]);
        $this->setDescription('Pas de description.');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(?\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    /**
     * @return Collection|Collaborateur[]
     */
    public function getCollabos(): Collection
    {
        return $this->collabos;
    }

    public function addCollabo(Collaborateur $collabo): self
    {
        if (!$this->collabos->contains($collabo)) {
            $this->collabos[] = $collabo;
            $collabo->addProjet($this);

        }

        return $this;
    }

    public function removeCollabo(Collaborateur $collabo): self
    {
        if ($this->collabos->contains($collabo)) {
            $this->collabos->removeElement($collabo);
        }

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->Service;
    }

    public function setService(?Service $Service): self
    {
        $this->Service = $Service;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
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
}
