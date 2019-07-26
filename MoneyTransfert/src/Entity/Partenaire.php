<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
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
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
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
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdminPartenaire", mappedBy="partenaire")
     */
    private $AdminP;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BankAccount", mappedBy="partenaire", cascade={"persist", "remove"})
     */
    private $bankAccount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPartenaire", mappedBy="partenaire")
     */
    private $userP;

    public function __construct()
    {
        $this->AdminP = new ArrayCollection();
        $this->userP = new ArrayCollection();
    }


    
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

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
     * @return Collection|AdminPartenaire[]
     */
    public function getAdminP(): Collection
    {
        return $this->AdminP;
    }

    public function addAdminP(AdminPartenaire $adminP): self
    {
        if (!$this->AdminP->contains($adminP)) {
            $this->AdminP[] = $adminP;
            $adminP->setPartenaire($this);
        }

        return $this;
    }

    public function removeAdminP(AdminPartenaire $adminP): self
    {
        if ($this->AdminP->contains($adminP)) {
            $this->AdminP->removeElement($adminP);
            // set the owning side to null (unless already changed)
            if ($adminP->getPartenaire() === $this) {
                $adminP->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        // set the owning side of the relation if necessary
        if ($this !== $bankAccount->getPartenaire()) {
            $bankAccount->setPartenaire($this);
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
            $userP->setPartenaire($this);
        }

        return $this;
    }

    public function removeUserP(UserPartenaire $userP): self
    {
        if ($this->userP->contains($userP)) {
            $this->userP->removeElement($userP);
            // set the owning side to null (unless already changed)
            if ($userP->getPartenaire() === $this) {
                $userP->setPartenaire(null);
            }
        }

        return $this;
    }
}
