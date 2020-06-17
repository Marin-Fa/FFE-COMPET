<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContestRepository")
 */
class Contest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=90)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=5,
     *     minMessage="adress.too_short",
     *     max=90,
     *     maxMessage="adress.too_long"
     * )
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     minMessage="zipcode.too_short",
     *     max=10,
     *     maxMessage="zipcode.too_long"
     * )
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     minMessage="city.too_short",
     *     max=80,
     *     maxMessage="city.too_long"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     minMessage="country.too_short",
     *     max=80,
     *     maxMessage="counrty.too_long"
     * )
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $end_of_registration;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     minMessage="country.too_short",
     *     max=50,
     *     maxMessage="counrty.too_long"
     * )
     */
    private $discipline;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    private $max_contestants_total;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $beginning_date;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $end_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contests" , cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="contest", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $stable_name;
    

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getEndOfRegistration(): ?\DateTimeInterface
    {
        return $this->end_of_registration;
    }

    public function setEndOfRegistration(\DateTimeInterface $end_of_registration): self
    {
        $this->end_of_registration = $end_of_registration;

        return $this;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): self
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getMaxContestantsTotal(): ?int
    {
        return $this->max_contestants_total;
    }

    public function setMaxContestantsTotal(int $max_contestants_total): self
    {
        $this->max_contestants_total = $max_contestants_total;

        return $this;
    }

    public function getBeginningDate(): ?\DateTimeInterface
    {
        return $this->beginning_date;
    }

    public function setBeginningDate(\DateTimeInterface $beginning_date): self
    {
        $this->beginning_date = $beginning_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setContest($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            if ($event->getContest() === $this) {
                $event->setContest(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getStableName(): ?string
    {
        return $this->stable_name;
    }

    public function setStableName(string $stable_name): self
    {
        $this->stable_name = $stable_name;

        return $this;
    }

    public function isPrivate()
    {

    }
}
