<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Order;

interface DiscountStrategy
{
    /**
     * @param Order $order
     * @return Discount[]
     */
    public function calculateDiscounts(Order $order): array;

    /**
     * @param Discount[] $discounts
     */
    public function updateOrder(Order $order, array $discounts): Order;
}
