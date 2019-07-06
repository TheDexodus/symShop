<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @Table(name="ord")
 */
class Order
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_WAITING = 'waiting';
    public const STATUS_CANCELED = 'canceled';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @var User $user
     */
    private $user;

    /**
     * @Assert\Count(min=1)
     * @ORM\ManyToMany(targetEntity="App\Entity\Position", cascade={"persist"})
     * @var Collection|Position[] $positions
     */
    private $positions;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"success", "waiting", "canceled"})
     * @ORM\Column(type="string", length=255)
     * @var string $status
     */
    private $status;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="float")
     * @var int $laterPrice
     */
    private $laterPrice;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Order
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    /**
     * @param Position $position
     * @return Order
     */
    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
        }

        return $this;
    }

    /**
     * @param Collection|Position[] $position
     * @return Order
     */
    public function setPositions(Collection $position): self
    {
        $this->positions = $position;

        return $this;
    }

    /**
     * @param Position $position
     * @return Order
     */
    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
        }
        return $this;
    }

    /**
     * @return string|null
     */
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
     * @return \DateTimeInterface|null
     */
    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTimeInterface $createdDate
     * @return Order
     */
    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLaterPrice(): ?float
    {
        return $this->laterPrice;
    }

    /**
     * @param float $laterPrice
     * @return Order
     */
    public function setLaterPrice(float $laterPrice): self
    {
        $this->laterPrice = $laterPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function calculateLaterPrice(): float
    {
        $price = 0;
        foreach ($this->getPositions() as $position) {
            $price += $position->getProduct()->getPrice() * $position->getCount();
        }

        return $price;
    }
}
