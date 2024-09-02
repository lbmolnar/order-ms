<?php

declare(strict_types=1);

namespace App\Application\Order\Application\CreateDiscounts;

use App\Application\Order\Domain\Model\Order;
use App\Application\Order\Domain\Service\DiscountCalculationRule;
use App\Application\Order\Domain\Service\ProductRepository;

class CreateDiscountsCommandHandler
{
    public function __construct(
        private readonly DiscountCalculationRule $discountCalculationRule,
        private readonly ProductRepository $repository,
    ) {
    }

    public function handle(CreateDiscountsCommand $command): array
    {
        $productIds = array_reduce(
            $command->items,
            function (array $carry, array $item): array {
                $carry[] = $item['product-id'];

                return $carry;
            },
            []
        );
        $order = Order::createFromData(
            $command->id,
            $command->customerId,
            $command->total,
            $command->items,
            $this->repository->getProductsByIds($productIds)
        );

        return $this->discountCalculationRule->calculateDiscounts($order);
    }
}
