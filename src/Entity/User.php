<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

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
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     */
    private $licence_number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contest", mappedBy="user", orphanRemoval=true)
     */
    private $contests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horserider", mappedBy="user", orphanRemoval=true)
     */
    private $horseriders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horse", mappedBy="user", cascade={"persist", "remove"})
     */
    private $horses;

    private $role;

    public function __construct()
    {
        $this->contests = new ArrayCollection();
        $this->horseriders = new ArrayCollection();
        $this->tests = new ArrayCollection();
        $this->horses = new ArrayCollection();
    }

    /**
     * @return Collection|Horse[]
     */
    public function getHorses(): Collection
    {
        return $this->horses;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_RIDER';
        return array_unique($roles);
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function resetRoles()
    {
        $this->roles = [];
    }

    public function getRoleAdmin()
    {
        $role = $this->role;

        $role[] = 'ROLE_ADMIN';

        return array_unique($role);
    }

    public function getRoleOrganizer()
    {
        $role = $this->role;

        $role[] = 'ROLE_ORGANIZER';

        return array_unique($role);
    }

    public function getRoleRider()
    {
        $role = $this->role;

        $role[] = 'ROLE_RIDER';

        return array_unique($role);
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getLicenceNumber(): ?string
    {
        return $this->licence_number;
    }

    public function setLicenceNumber(string $licence_number): self
    {
        $this->licence_number = $licence_number;

        return $this;
    }

    /**
     * @return Collection|Contest[]
     */
    public function getContests(): Collection
    {
        return $this->contests;
    }

    public function addContest(Contest $contest): self
    {
        if (!$this->contests->contains($contest)) {
            $this->contests[] = $contest;
            $contest->setUser($this);
        }

        return $this;
    }

    public function removeContest(Contest $contest): self
    {
        if ($this->contests->contains($contest)) {
            $this->contests->removeElement($contest);
            // set the owning side to null (unless already changed)
            if ($contest->getUser() === $this) {
                $contest->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Horserider[]
     */
    public function getHorseriders(): Collection
    {
        return $this->horseriders;
    }

    public function addHorserider(Horserider $horserider): self
    {
        if (!$this->horseriders->contains($horserider)) {
            $this->horseriders[] = $horserider;
            $horserider->setUser($this);
        }

        return $this;
    }

    public function removeHorserider(Horserider $horserider): self
    {
        if ($this->horseriders->contains($horserider)) {
            $this->horseriders->removeElement($horserider);
            // set the owning side to null (unless already changed)
            if ($horserider->getUser() === $this) {
                $horserider->setUser(null);
            }
        }

        return $this;
    }

    public function addHorse(Horse $horse): self
    {
        if (!$this->horses->contains($horse)) {
            $this->horses[] = $horse;
            $horse->setUser($this);
        }

        return $this;
    }

    public function removeHorse(Horse $horse): self
    {
        if ($this->horses->contains($horse)) {
            $this->horses->removeElement($horse);
            // set the owning side to null (unless already changed)
            if ($horse->getUser() === $this) {
                $horse->setUser(null);
            }
        }

        return $this;
    }
}
