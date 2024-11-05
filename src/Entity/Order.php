<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: "App\Repository\OrderRepository")]
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

    // Getters and setters...
}
