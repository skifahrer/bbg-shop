<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentType;

#[ORM\Entity(repositoryClass: "App\Repository\CheckoutRepository")]
class Checkout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

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

    public function getPaymentType(): PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;
        return $this;
    }
}
