<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Discount\DiscountType;
use App\Application\Order\Domain\Model\Discount\ExtraProductDiscount;
use App\Application\Order\Domain\Model\Order;
use App\Application\Order\Domain\Model\OrderItem;

class SwitchExtraProductDiscount implements DiscountStrategy
{
    private const int DISCOUNTED_PRODUCT_ID = 2;
    private const int QUANTITY_THRESHOLD = 5;
    private const int DISCOUNTED_AMOUNT = 1;

    public function calculateDiscounts(Order $order): array
    {
        $discounts = [];
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();
            if (self::DISCOUNTED_PRODUCT_ID === $product->getCategory() && self::QUANTITY_THRESHOLD <= $orderItem->getQuantity()) {
                $discounts[] = new Discount(
                    DiscountType::EXTRA_PRODUCT,
                    new ExtraProductDiscount($product, self::DISCOUNTED_AMOUNT),
                );
            }
        }

        return $discounts;
    }

    /**
     * @param Discount[] $discounts
     */
    public function updateOrder(Order $order, array $discounts): Order
    {
        $items = $order->getItems();
        foreach ($discounts as $discount) {
            /** @var ExtraProductDiscount $discountObject */
            $discountObject = $discount->getDiscountObject();
            $items[] = new OrderItem($discountObject->getProduct(), $discountObject->getQuantity(), 0);
        }

        return new Order($order->getId(), $order->getCustomerId(), $order->getTotal(), $items);
    }
}
