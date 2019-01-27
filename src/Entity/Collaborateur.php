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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Service")
     */
    private $ServiceChef;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Projet",fetch="EAGER")
     * @ORM\JoinTable(name="projet_collaborateur")
     */
    private $projets;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $email;

    public function __construct()
    {
        $this->ServiceChef = new ArrayCollection();
        $this->projet = new ArrayCollection();
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

    public function setPassword(string $pass): self
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
            'ROLE_USER'
        ];
    }

    public function setRoles(array $roles):self
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
