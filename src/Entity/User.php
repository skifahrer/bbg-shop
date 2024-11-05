<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'user')]
    private $carts;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private $orders;

    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'user')]
    private $checkouts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $shippingAddress;

    // Getters and setters...
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setPassword(string $password): self
    {
        $this->password = $this->hashPassword($password);
        return $this;
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
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
