<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\OrderStatus;

#[ORM\Entity(repositoryClass: "App\Repository\OrderRepository")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

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

}
