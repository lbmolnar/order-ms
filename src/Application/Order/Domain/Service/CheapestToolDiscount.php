<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Discount\DiscountType;
use App\Application\Order\Domain\Model\Discount\OrderLineReplacementDiscount;
use App\Application\Order\Domain\Model\Order;
use App\Application\Order\Domain\Model\OrderItem;

class CheapestToolDiscount implements DiscountStrategy
{
    private const int DISCOUNTED_PRODUCT_ID = 1;
    private const int QUANTITY_THRESHOLD = 2;
    private const int DISCOUNTED_PERCENT = 20;

    public function calculateDiscounts(Order $order): array
    {
        $discounts = [];

        $orderItemQualifiedForDiscount = $this->getOrderItemQualifiedForDiscount($order);

        if (null === $orderItemQualifiedForDiscount) {
            return $discounts;
        }

        $productUnitPrice = $orderItemQualifiedForDiscount->getProduct()->getPrice();
        $newItemPrice = $productUnitPrice - ($productUnitPrice * (self::DISCOUNTED_PERCENT / 100));

        return [
            new Discount(
                DiscountType::ORDER_LINE_REPLACEMENT,
                new OrderLineReplacementDiscount(
                    new OrderItem(
                        $orderItemQualifiedForDiscount->getProduct(),
                        $orderItemQualifiedForDiscount->getQuantity(),
                        $orderItemQualifiedForDiscount->getTotal() - $productUnitPrice + $newItemPrice
                    ),
                    $this->getProductLineNumber($orderItemQualifiedForDiscount, $order->getItems())
                )
            )
        ];
    }

    /**
     * @param Discount[] $discounts
     */
    public function updateOrder(Order $order, array $discounts): Order
    {
        $items = $order->getItems();
        $total = $order->getTotal();
        foreach ($discounts as $discount) {
            /** @var OrderLineReplacementDiscount $discountObject */
            $discountObject = $discount->getDiscountObject();
            $total -= $items[$discountObject->getOrderLineReplaced()]->getTotal();
            $total += $discountObject->getNewOrderItem()->getTotal();
            $items[$discountObject->getOrderLineReplaced()] = $discountObject->getNewOrderItem();
        }

        return new Order($order->getId(), $order->getCustomerId(), $total, $items);
    }

    private function getOrderItemQualifiedForDiscount(Order $order): ?OrderItem
    {
        $orderItems = $this->getDiscountedProductsOrderedByPrice($order);
        $firstOrderItem = $orderItems[0] ?? null;

        if (
            self::QUANTITY_THRESHOLD <= count($orderItems)
            || self::QUANTITY_THRESHOLD <= $firstOrderItem?->getQuantity()
        ) {
            return $firstOrderItem;
        }

        return null;
    }

    private function getDiscountedProductsOrderedByPrice(Order $order): array
    {
        $orderItems = $order->getItems();
        $orderItems = array_filter(
            $orderItems,
            function (OrderItem $item): bool {
                return self::DISCOUNTED_PRODUCT_ID === $item->getProduct()->getCategory();
            }
        );
        usort(
            $orderItems,
            fn(OrderItem $a, OrderItem $b): int => $a->getProduct()->getPrice() <=> $b->getProduct()->getPrice()
        );

        return $orderItems;
    }

    private function getProductLineNumber(OrderItem $searchOrderItem, array $orderItems): int
    {
        $lineNumber = -1;

        foreach ($orderItems as $key => $orderItem) {
            if ($searchOrderItem === $orderItem) {
                $lineNumber = $key;
                break;
            }
        }

        return $lineNumber;
    }
}
