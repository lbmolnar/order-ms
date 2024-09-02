<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Service;

interface ProductRepository
{
    public function getProductsByIds(array $ids): array;
}
