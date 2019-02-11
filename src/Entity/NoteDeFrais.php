<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteDeFraisRepository")
 */
class NoteDeFrais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     */
    private $mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $collabo;

    const STATUS = [
        0 => 'En cours',
        1 => 'En attente chef',
        2 => 'validee chef',
        3 => 'refusee chef',
        4 => 'En attente compta',
        5 => 'validee compta',
        6 => 'valideeExceptJustificatif',
        7 => 'refusee compta',
    ];

    public function __construct($mois, $annee, $collabo)
    {
        $this->setMontant(0);
        $this->setStatut("En cours");
        $this->setMois($mois);
        $this->setAnnee($annee);
        $this->setCollabo($collabo);

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

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

    public function getCollabo(): ?Collaborateur
    {
        return $this->collabo;
    }

    public function setCollabo(?Collaborateur $collabo): self
    {
        $this->collabo = $collabo;

        return $this;
    }

    public function getLastModif(): ?\DateTimeInterface
    {
        return $this->lastModif;
    }

    public function setLastModif(?\DateTimeInterface $lastModif): self
    {
        $this->lastModif = $lastModif;

        return $this;
    }
}
