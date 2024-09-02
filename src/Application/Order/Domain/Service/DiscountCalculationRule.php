<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Order;

interface DiscountCalculationRule
{
    /**
     * @return array<int, Discount>
     */
    public function calculateDiscounts(Order &$order): array;
}
