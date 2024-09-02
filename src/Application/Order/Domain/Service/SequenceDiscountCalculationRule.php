<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Order;

class SequenceDiscountCalculationRule implements DiscountCalculationRule
{
    /**
     * @param array<int, DiscountStrategy> $discountStrategies
     */
    public function __construct(private readonly array $discountStrategies)
    {
    }

    public function calculateDiscounts(Order &$order): array
    {
        $allDiscounts = [];
        foreach ($this->discountStrategies as $discountStrategy) {
            $discounts = array_filter($discountStrategy->calculateDiscounts($order));
            $order = $discountStrategy->updateOrder($order, $discounts);
            $allDiscounts = array_merge($allDiscounts, $discounts);
        }

        return $allDiscounts;
    }
}
