<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Order\Application\CreateDiscounts\CreateDiscountsCommand;
use App\Application\Order\Application\CreateDiscounts\CreateDiscountsCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class DiscountController
{
    public function __construct(private readonly CreateDiscountsCommandHandler $handler)
    {
    }

    #[Route('/order/discount', name: 'discount', methods: ['POST'])]
    public function getDiscounts(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $discounts = $this->handler->handle(new CreateDiscountsCommand(
            (int)$data['id'] ?? null,
            (int)$data['customer-id'] ?? null,
            (float)$data['total'] ?? null,
            $data['items'] ?? [],
        ));

        return new JsonResponse(['data' => $discounts]);
    }
}
