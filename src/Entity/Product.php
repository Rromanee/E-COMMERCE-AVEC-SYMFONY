<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isFeatured = false;

    #[ORM\Column(nullable: true)]
    private ?int $stockXs = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockS = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockM = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockL = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockXl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getStockXs(): ?int
    {
        return $this->stockXs;
    }

    public function setStockXs(?int $stockXs): static
    {
        $this->stockXs = $stockXs;

        return $this;
    }

    public function getStockS(): ?int
    {
        return $this->stockS;
    }

    public function setStockS(?int $stockS): static
    {
        $this->stockS = $stockS;

        return $this;
    }

    public function getStockM(): ?int
    {
        return $this->stockM;
    }

    public function setStockM(?int $stockM): static
    {
        $this->stockM = $stockM;

        return $this;
    }

    public function getStockL(): ?int
    {
        return $this->stockL;
    }

    public function setStockL(?int $stockL): static
    {
        $this->stockL = $stockL;

        return $this;
    }

    public function getStockXl(): ?int
    {
        return $this->stockXl;
    }

    public function setStockXl(?int $stockXl): static
    {
        $this->stockXl = $stockXl;

        return $this;
    }
}
