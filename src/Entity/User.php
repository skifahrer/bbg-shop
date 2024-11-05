<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]

class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column(type: "string")]
    private $password;

    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'user')]
    private $carts;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private $orders;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $shippingAddress;

    // Getters and setters...
    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->id;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }
}
