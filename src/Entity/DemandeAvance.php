<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeAvanceRepository")
 */
class DemandeAvance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateur", inversedBy="demandeAvances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $collabo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LigneDeFrais", inversedBy="demandeAvances")
     */
    private $lignes;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    const  STATUS = [
       0 => 'En cours de validation',
       1 => 'accepete',
       2 => 'refusee',
    ];

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->setMontant(0);
        $this->setStatut(DemandeAvance::STATUS[0]);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|LigneDeFrais[]
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(LigneDeFrais $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
        }

        return $this;
    }

    public function removeLigne(LigneDeFrais $ligne): self
    {
        if ($this->lignes->contains($ligne)) {
            $this->lignes->removeElement($ligne);
        }

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): self
    {
        $this->montant = $montant;

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
}
