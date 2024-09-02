<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model;

use JsonSerializable;

readonly class OrderItem implements JsonSerializable
{
    public function __construct(
        private Product $product,
        private int $quantity,
        private float $total,
    ) {
    }

    public static function createFromArray(array $data, array $products): self
    {
        return new self(
            $products[$data['product-id']],
            (int)$data['quantity'],
            (float)$data['total'],
        );
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function jsonSerialize(): array
    {
        return [
            'product-id' => $this->product->getId(),
            'quantity' => $this->quantity,
            'unit-price' => $this->product->getPrice(),
            'total' => $this->total,
        ];
    }
}
