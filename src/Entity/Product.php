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

    #[ORM\Column(type: 'string', length: 255, unique: true)]
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

    public function setId(UuidInterface $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;
        return $this;
    }

    public function getItem(): string
    {
        return $this->item;
    }

    public function setOxprice(float $oxprice): self
    {
        $this->oxprice = $oxprice;
        return $this;
    }

    public function getOxprice(): float
    {
        return $this->oxprice;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setTitleEn(string $title_en): self
    {
        $this->title_en = $title_en;
        return $this;
    }

    public function getTitleEn(): string
    {
        return $this->title_en;
    }

    public function setTitleSk(string $title_sk): self
    {
        $this->title_sk = $title_sk;
        return $this;
    }

    public function getTitleSk(): string
    {
        return $this->title_sk;
    }

    public function setTitleSl(string $title_sl): self
    {
        $this->title_sl = $title_sl;
        return $this;
    }

    public function getTitleSl(): string
    {
        return $this->title_sl;
    }

    public function setTitleHu(string $title_hu): self
    {
        $this->title_hu = $title_hu;
        return $this;
    }

    public function getTitleHu(): string
    {
        return $this->title_hu;
    }

    public function setTitleHr(string $title_hr): self
    {
        $this->title_hr = $title_hr;
        return $this;
    }

    public function getTitleHr(): string
    {
        return $this->title_hr;
    }

    public function setTitleRo(string $title_ro): self
    {
        $this->title_ro = $title_ro;
        return $this;
    }

    public function getTitleRo(): string
    {
        return $this->title_ro;
    }

    public function setTitleBg(string $title_bg): self
    {
        $this->title_bg = $title_bg;
        return $this;
    }

    public function getTitleBg(): string
    {
        return $this->title_bg;
    }

    public function setFeaturesJson(array $features_json): self
    {
        $this->features_json = $features_json;
        return $this;
    }

    public function getFeaturesJson(): array
    {
        return $this->features_json;
    }

    public function getLocalizedTitle(string $locale = 'en'): string
    {
        return match ($locale) {
            'sk' => $this->getTitleSk(),
            'sl' => $this->getTitleSl(),
            'hu' => $this->getTitleHu(),
            'hr' => $this->getTitleHr(),
            'ro' => $this->getTitleRo(),
            'bg' => $this->getTitleBg(),
            default => $this->getTitleEn(),
        };
    }
}
