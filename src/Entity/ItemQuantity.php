<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: "App\Repository\ItemQuantityRepository")]
class ItemQuantity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'itemQuantities')]
    #[ORM\JoinColumn(nullable: true)]
    private $cart;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'itemQuantities')]
    #[ORM\JoinColumn(nullable: true)]
    private $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    // Getters and setters...
}
