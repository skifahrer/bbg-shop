<?php

namespace App\Entity;

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

    #[ORM\OneToMany(targetEntity: ItemQuantity::class, mappedBy: 'cart')]
    #[Groups(['cart:read'])]
    private $itemQuantities;

    #[ORM\OneToOne(targetEntity: Checkout::class, mappedBy: 'cart')]
    #[Groups(['cart:read'])]
    private $checkout;

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

    public function getItemQuantities()
    {
        return $this->itemQuantities;
    }

    public function setItemQuantities($itemQuantities): self
    {
        $this->itemQuantities = $itemQuantities;
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
