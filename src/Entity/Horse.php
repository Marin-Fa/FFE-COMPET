<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HorseRepository")
 */
class Horse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     minMessage="name.too_short",
     *     max=100,
     *     maxMessage="name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     minMessage="gender.too_short",
     *     max=40,
     *     maxMessage="gender.too_long"
     * )
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horserider", mappedBy="horse", orphanRemoval=true)
     */
    private $horseriders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="horses")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->horseriders = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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
            $horserider->setHorse($this);
        }

        return $this;
    }

    public function removeHorserider(Horserider $horserider): self
    {
        if ($this->horseriders->contains($horserider)) {
            $this->horseriders->removeElement($horserider);
            // set the owning side to null (unless already changed)
            if ($horserider->getHorse() === $this) {
                $horserider->setHorse(null);
            }
        }

        return $this;
    }

    public function _toString() {
        return (string) $this->getName();
    }

}
