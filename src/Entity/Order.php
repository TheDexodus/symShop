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
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\Count(min=1)
     * @ORM\ManyToMany(targetEntity="App\Entity\Product")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices = {"success", "waiting", "canceled"})
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="float")
     */
    private $laterPrice;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }
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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getLaterPrice(): ?float
    {
        return $this->laterPrice;
    }

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
        foreach ($this->getProducts() as $product) {
            $price += $product->getPrice();
        }

        return $price;
    }
}
