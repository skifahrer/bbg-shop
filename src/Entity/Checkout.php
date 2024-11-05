<?php

namespace App\Entity;

use App\Enum\PaymentType;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: "App\Repository\CheckoutRepository")]
class Checkout
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $cart;

    #[ORM\OneToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $order;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $shippingAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private $invoiceAddress;

    #[ORM\Column(type: 'string', enumType: PaymentType::class)]
    private $paymentType;

    // Getters and setters...
}
