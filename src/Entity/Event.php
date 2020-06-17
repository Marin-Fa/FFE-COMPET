<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=5,
     *     minMessage="description.too_short",
     *     max=255,
     *     maxMessage="description.too_long"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $level;

    /**
     * @ORM\Column(type="time")
     */
    private $estimated_starting_time;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    private $max_contestants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contest", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contest;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horserider", mappedBy="event", orphanRemoval=true)
     */
    private $horseriders;

    public function __construct()
    {
        $this->horseriders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getEstimatedStartingTime(): ?\DateTimeInterface
    {
        return $this->estimated_starting_time;
    }

    public function setEstimatedStartingTime(\DateTimeInterface $estimated_starting_time): self
    {
        $this->estimated_starting_time = $estimated_starting_time;

        return $this;
    }

    public function getMaxContestants(): ?int
    {
        return $this->max_contestants;
    }

    public function setMaxContestants(int $max_contestants): self
    {
        $this->max_contestants = $max_contestants;

        return $this;
    }

    public function getContest(): ?Contest
    {
        return $this->contest;
    }

    public function setContest(?Contest $contest): self
    {
        $this->contest = $contest;

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
            $horserider->setEvent($this);
        }

        return $this;
    }

    public function removeHorserider(Horserider $horserider): self
    {
        if ($this->horseriders->contains($horserider)) {
            $this->horseriders->removeElement($horserider);
            // set the owning side to null (unless already changed)
            if ($horserider->getEvent() === $this) {
                $horserider->setEvent(null);
            }
        }

        return $this;
    }
}
