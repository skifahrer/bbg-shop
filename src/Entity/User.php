<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 180)]
    private $name;

    #[ORM\Column(type: 'string', length: 180)]
    private $family;

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
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFamily(): string
    {
        return $this->family;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCarts()
    {
        return $this->carts;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
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

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;  // Changed from id to email
    }

    public static function createFromPayload($email, array $payload)
    {
        $user = new self();
        $user->setEmail($email);
        $user->setRoles($payload['roles'] ?? ['ROLE_USER']);
        return $user;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(mixed $shipping_address)
    {
        $this->shippingAddress = $shipping_address;
        return $this;
    }
}
