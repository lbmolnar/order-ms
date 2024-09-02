<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model;

readonly class Order
{
    /**
     * @param int $id
     * @param int $customerId
     * @param float $total
     * @param OrderItem[] $items
     */
    public function __construct(private int $id, private int $customerId, private float $total, private array $items)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public static function createFromData(int $id, int $customerId, float $total, array $items, array $products): Order
    {
        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[] = OrderItem::createFromArray($item, $products);
        }

        return new self($id, $customerId, $total, $orderItems);
    }
}
