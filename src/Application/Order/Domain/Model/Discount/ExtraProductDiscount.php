<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model\Discount;

use App\Application\Order\Domain\Model\Product;

class ExtraProductDiscount extends DiscountObject
{
    public function __construct(
        private readonly Product $product,
        private readonly int $quantity,
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function jsonSerialize(): array
    {
        return $this->product->jsonSerialize()
            + [
                'quantity' => $this->quantity,
            ];
    }
}
