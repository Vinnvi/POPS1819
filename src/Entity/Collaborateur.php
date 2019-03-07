<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CollaborateurRepository")
 */
class Collaborateur implements UserInterface,EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $profile_pic_path;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $background_pic_path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(nullable=true)
     */
    public $service;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Service")
     */
    public $ServiceChef;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Projet")
     */
    private $projets;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    public $email;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="collaborateur")
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $role_entreprise;

    /**
     * @ORM\Column(type="integer")
     */
    public $rtt;

    /**
     * @ORM\Column(type="integer")
     */
    public $conge;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conge", mappedBy="collabo")
     */
    public $conges;

    const STATUS = [
        0 => 'Collaborateur',
        1 => 'Chef de service',
        2 => 'directeur',
    ];

    public function __construct()
    {
        $this->ServiceChef = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->setRoles('ROLE_USER');
        $this->setRoleEntreprise(Collaborateur::STATUS[0]);
        $this->setSalt('');
        $this->setProfilePicPath('images/profile_pics/anonym_picture.jpg');
        $this->setBackgroundPicPath('images/profile_pics/backgroundProfil.png');

        $this->notifications = new ArrayCollection();
        $this->conges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles()
    {
        return [
            $this->roles
        ];
    }

    public function setRoles(string $roles):self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt(string $salt):self
    {
        $this->salt = $salt;
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
      if (!$user instanceof Collaborateur) {
          return false;
      }

      if ($this->password !== $user->getPassword()) {
          return false;
      }

      if ($this->salt !== $user->getSalt()) {
          return false;
      }

      if ($this->username !== $user->getUsername()) {
          return false;
      }

      return true;
    }

    public function getprofile_pic_path(): string
    {
        return $this->profile_pic_path;
    }

    public function setProfilePicPath(string $profile_pic_path): self
    {
        $this->profile_pic_path = $profile_pic_path;

        return $this;
    }

    public function getBackground_pic_path(): string
    {
        if($this->background_pic_path === NULL)
        {
          return "images/backgroundProfil.png";
        }
        return $this->background_pic_path;
    }

    public function setBackgroundPicPath(string $background_pic_path): self
    {
        $this->background_pic_path = $background_pic_path;

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

    /**
     * @return Collection|Service[]
     */
    public function getServiceChef(): Collection
    {
        return $this->ServiceChef;
    }

    public function addServiceChef(Service $serviceChef): self
    {
        if (!$this->ServiceChef->contains($serviceChef)) {
            $this->ServiceChef[] = $serviceChef;
        }

        return $this;
    }

    public function removeServiceChef(Service $serviceChef): self
    {
        if ($this->ServiceChef->contains($serviceChef)) {
            $this->ServiceChef->removeElement($serviceChef);
        }

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }


    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->contains($projet)) {
            $this->projets->removeElement($projet);
            // set the owning side to null (unless already changed)
        }
        return $this;
    }





    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setCollaborateur($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getCollaborateur() === $this) {
                $notification->setCollaborateur(null);
            }
        }

        return $this;
    }

    public function getRoleEntreprise(): ?string
    {
        return $this->role_entreprise;
    }

    public function getrole_entreprise(): ?string
    {
        return $this->role_entreprise;
    }

    public function setRoleEntreprise(string $role_entreprise): self
    {
        $this->role_entreprise = $role_entreprise;

        return $this;
    }

    public function getRtt(): ?int
    {
        return $this->rtt;
    }

    public function setRtt(int $rtt): self
    {
        $this->rtt = $rtt;

        return $this;
    }

    public function getConge(): ?int
    {
        return $this->conge;
    }

    public function setConge(int $conge): self
    {
        $this->conge = $conge;

        return $this;
    }

    /**
     * @return Collection|Conge[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conge $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setCollabo($this);
        }

        return $this;
    }

    public function removeConge(Conge $conge): self
    {
        if ($this->conges->contains($conge)) {
            $this->conges->removeElement($conge);
            // set the owning side to null (unless already changed)
            if ($conge->getCollabo() === $this) {
                $conge->setCollabo(null);
            }
        }

        return $this;
    }
}
