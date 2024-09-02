<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model\Discount;

class AmountDiscount extends DiscountObject
{
    public function __construct(private readonly float $amount)
    {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function jsonSerialize(): array
    {
        return ['amount' => $this->amount];
    }
}
