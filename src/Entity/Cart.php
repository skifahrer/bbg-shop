<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CartRepository")]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(targetEntity: ItemQuantity::class, mappedBy: 'cart')]
    private $itemQuantities;

    #[ORM\OneToOne(targetEntity: Checkout::class, mappedBy: 'cart')]
    private $checkout;

    // Getters and setters...
}
