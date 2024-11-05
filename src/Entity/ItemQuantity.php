<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ItemQuantityRepository")]
class ItemQuantity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

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
