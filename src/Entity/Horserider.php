<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HorseriderRepository")
 */
class Horserider
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    private $start_number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="horseriders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="horseriders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Horse", inversedBy="horseriders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $horse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartNumber(): ?int
    {
        return $this->start_number;
    }

    public function setStartNumber(int $start_number): self
    {
        $this->start_number = $start_number;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
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

    public function getHorse(): ?Horse
    {
        return $this->horse;
    }

    public function setHorse(?Horse $horse): self
    {
        $this->horse = $horse;

        return $this;
    }
}
