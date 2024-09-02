<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Discount\AmountDiscount;
use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Discount\DiscountType;
use App\Application\Order\Domain\Model\Order;

class FlatDiscountOnTotalOrder implements DiscountStrategy
{
    private const float TOTAL_ORDER_THRESHOLD = 1000;
    private const int DISCOUNT_PERCENT = 10;

    public function calculateDiscounts(Order $order): array
    {
        $orderTotal = $order->getTotal();

        if (self::TOTAL_ORDER_THRESHOLD <= $orderTotal) {
            return [
                new Discount(
                    DiscountType::TOTAL,
                    new AmountDiscount(round($orderTotal / self::DISCOUNT_PERCENT, 2))
                )
            ];
        }

        return [];
    }

    public function updateOrder(Order $order, array $discounts): Order
    {
        $discount = $discounts[0] ?? null;
        /** @var ?AmountDiscount $discountObject */
        $discountObject = $discount?->getDiscountObject();

        if (null !== $discount) {
            return new Order(
                $order->getId(),
                $order->getCustomerId(),
                round($order->getTotal() - ($discountObject?->getAmount() ?? 0), 2),
                $order->getItems()
            );
        }

        return $order;
    }
}
