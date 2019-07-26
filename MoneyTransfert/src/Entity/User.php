<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déjà")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partenaire", mappedBy="user")
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdminPartenaire", mappedBy="user")
     */
    private $adminP;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPartenaire", mappedBy="user")
     */
    private $userP;

    public function __construct()
    {
        $this->partenaire = new ArrayCollection();
        $this->adminP = new ArrayCollection();
        $this->userP = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(string $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getPartenaire(): Collection
    {
        return $this->partenaire;
    }

    public function addPartenaire(Partenaire $partenaire): self
    {
        if (!$this->partenaire->contains($partenaire)) {
            $this->partenaire[] = $partenaire;
            $partenaire->setUser($this);
        }

        return $this;
    }

    public function removePartenaire(Partenaire $partenaire): self
    {
        if ($this->partenaire->contains($partenaire)) {
            $this->partenaire->removeElement($partenaire);
            // set the owning side to null (unless already changed)
            if ($partenaire->getUser() === $this) {
                $partenaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdminPartenaire[]
     */
    public function getAdminP(): Collection
    {
        return $this->adminP;
    }

    public function addAdminP(AdminPartenaire $adminP): self
    {
        if (!$this->adminP->contains($adminP)) {
            $this->adminP[] = $adminP;
            $adminP->setUser($this);
        }

        return $this;
    }

    public function removeAdminP(AdminPartenaire $adminP): self
    {
        if ($this->adminP->contains($adminP)) {
            $this->adminP->removeElement($adminP);
            // set the owning side to null (unless already changed)
            if ($adminP->getUser() === $this) {
                $adminP->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserPartenaire[]
     */
    public function getUserP(): Collection
    {
        return $this->userP;
    }

    public function addUserP(UserPartenaire $userP): self
    {
        if (!$this->userP->contains($userP)) {
            $this->userP[] = $userP;
            $userP->setUser($this);
        }

        return $this;
    }

    public function removeUserP(UserPartenaire $userP): self
    {
        if ($this->userP->contains($userP)) {
            $this->userP->removeElement($userP);
            // set the owning side to null (unless already changed)
            if ($userP->getUser() === $this) {
                $userP->setUser(null);
            }
        }

        return $this;
    }
}