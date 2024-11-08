<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "App\Repository\CartRepository")]
class Cart
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    #[Groups(['cart:read'])]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(targetEntity: ItemQuantity::class, mappedBy: 'cart', cascade: ['persist', 'remove'])]
    #[Groups(['cart:read'])]
    private $itemQuantities;

    #[ORM\OneToOne(targetEntity: Checkout::class, mappedBy: 'cart', cascade: ['persist', 'remove'])]
    #[Groups(['cart:read'])]
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

    /**
     * @return Collection<int, ItemQuantity>
     */
    public function getItemQuantities(): Collection
    {
        return $this->itemQuantities;
    }

    public function addItemQuantity(ItemQuantity $itemQuantity): self
    {
        if (!$this->itemQuantities->contains($itemQuantity)) {
            $this->itemQuantities[] = $itemQuantity;
            $itemQuantity->setCart($this);
        }

        return $this;
    }

    public function removeItemQuantity(ItemQuantity $itemQuantity): self
    {
        if ($this->itemQuantities->removeElement($itemQuantity)) {
            // set the owning side to null (unless already changed)
            if ($itemQuantity->getCart() === $this) {
                $itemQuantity->setCart(null);
            }
        }

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
