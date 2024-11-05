<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: "App\Repository\ProductRepository")]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $item;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private $oxprice;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private $weight;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\Column(type: 'integer')]
    private $length;

    #[ORM\Column(type: 'integer')]
    private $width;

    #[ORM\Column(type: 'integer')]
    private $height;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_en;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_sk;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_sl;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_hu;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_hr;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_ro;

    #[ORM\Column(type: 'string', length: 255)]
    private $title_bg;

    #[ORM\Column(type: 'json')]
    private $features_json;

    // Getters and setters...
}
