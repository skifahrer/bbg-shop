<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Index(name: 'idx_user_email', columns: ['email'])]
#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    #[Groups(['user:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups(['user:read'])]
    private $name;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups(['user:read'])]
    private $family;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read'])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read'])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'user')]
    #[Groups(['user:read'])]
    private $carts;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private $orders;

    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'user')]
    private $checkouts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $shippingAddress;

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
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(mixed $shipping_address)
    {
        $this->shippingAddress = $shipping_address;
    }

    public function getUserIdentifier(): string
    {
        return $this->getId()->toString();
    }

    public static function createFromPayload($id, array $payload): self
    {
        $user = new self();
        $user->id = Uuid::fromString($id);
        $user->setRoles($payload['roles'] ?? ['ROLE_USER']);
        return $user;
    }
}
