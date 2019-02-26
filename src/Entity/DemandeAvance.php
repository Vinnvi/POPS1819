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
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneDeFrais", mappedBy="demandeAvance")
     */
    private $Lignes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $Motif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastModif;

    const STATUS= [
        0 => 'En cours de validation',
        1 => 'Validee',
        2 => 'Refusee',
    ];

    public function __construct()
    {
        $this->Lignes = new ArrayCollection();
        $this->setStatut(DemandeAvance::STATUS[0]);
        $this->lastModif = new \DateTime();
        $this->setMontant(0);
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection|LigneDeFrais[]
     */
    public function getLignes(): Collection
    {
        return $this->Lignes;
    }

    public function addLigne(LigneDeFrais $ligne): self
    {
        if (!$this->Lignes->contains($ligne)) {
            $this->Lignes[] = $ligne;
            $ligne->setDemandeAvance($this);
        }

        return $this;
    }

    public function removeLigne(LigneDeFrais $ligne): self
    {
        if ($this->Lignes->contains($ligne)) {
            $this->Lignes->removeElement($ligne);
            // set the owning side to null (unless already changed)
            if ($ligne->getDemandeAvance() === $this) {
                $ligne->setDemandeAvance(null);
            }
        }

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

    public function getMotif(): ?string
    {
        return $this->Motif;
    }

    public function setMotif(?string $Motif): self
    {
        $this->Motif = $Motif;

        return $this;
    }

    public function getLastModif(): ?\DateTimeInterface
    {
        return $this->lastModif;
    }

    public function setLastModif(\DateTimeInterface $lastModif): self
    {
        $this->lastModif = $lastModif;

        return $this;
    }
}
