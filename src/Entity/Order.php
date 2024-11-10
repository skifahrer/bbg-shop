<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: "App\Repository\OrderRepository")]
#[ORM\Table(name: '`shop_order`')] // mysql reserved keyword
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\OneToMany(targetEntity: ItemQuantity::class, mappedBy: 'order')]
    private $itemQuantities;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private $finalPrice;

    #[ORM\Column(type: 'string', length: 255)]
    private $shippingAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private $invoiceAddress;

    #[ORM\Column(type: 'string', enumType: OrderStatus::class)]
    private $status;

    #[ORM\OneToOne(targetEntity: Checkout::class, mappedBy: 'order')]
    private $checkout;

    public function __construct()
    {
        $this->itemQuantities = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getItemQuantities(): Collection
    {
        return $this->itemQuantities;
    }

    public function addItemQuantity(ItemQuantity $itemQuantity): self
    {
        if (!$this->itemQuantities->contains($itemQuantity)) {
            $this->itemQuantities[] = $itemQuantity;
            $itemQuantity->setOrder($this);
        }

        return $this;
    }

    public function removeItemQuantity(ItemQuantity $itemQuantity): self
    {
        if ($this->itemQuantities->removeElement($itemQuantity)) {
            // set the owning side to null (unless already changed)
            if ($itemQuantity->getOrder() === $this) {
                $itemQuantity->setOrder(null);
            }
        }

        return $this;
    }

    public function getFinalPrice(): string
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(string $finalPrice): self
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getInvoiceAddress(): string
    {
        return $this->invoiceAddress;
    }

    public function setInvoiceAddress(string $invoiceAddress): self
    {
        $this->invoiceAddress = $invoiceAddress;

        return $this;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCheckout(): ?Checkout
    {
        return $this->checkout;
    }

    public function setCheckout(?Checkout $checkout): self
    {
        $this->checkout = $checkout;

        return $this;
    }
}
