<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model;

class Product implements \JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly string $description,
        private readonly int $category,
        private readonly float $price
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
        ];
    }
}
