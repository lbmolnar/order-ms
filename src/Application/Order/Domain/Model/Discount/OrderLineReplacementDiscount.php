<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model\Discount;

use App\Application\Order\Domain\Model\OrderItem;

class OrderLineReplacementDiscount extends DiscountObject
{
    public function __construct(private readonly OrderItem $newOrderItem, private readonly int $orderLineReplaced)
    {
    }

    public function getNewOrderItem(): OrderItem
    {
        return $this->newOrderItem;
    }

    public function getOrderLineReplaced(): int
    {
        return $this->orderLineReplaced;
    }

    public function jsonSerialize(): array
    {
        return [
                'orderLineReplaced' => $this->orderLineReplaced
            ]
            + $this->newOrderItem->jsonSerialize();
    }
}
