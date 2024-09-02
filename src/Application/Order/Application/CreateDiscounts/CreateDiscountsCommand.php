<?php

declare(strict_types=1);

namespace App\Application\Order\Application\CreateDiscounts;

readonly class CreateDiscountsCommand
{
    /**
     * @param array<int, array{product-id: string, quantity: string, unit-price: string, total: string}> $items
     */
    public function __construct(
        public int $id,
        public int $customerId,
        public float $total,
        public array $items,
    ) {
    }
}
