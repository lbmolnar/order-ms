<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Repository;

use App\Application\Order\Domain\Model\Product;
use App\Application\Order\Domain\Service\ProductRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;

class PostgresProductRepository implements ProductRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getProductsByIds(array $ids): array
    {
        $q = $this->entityManager->getConnection()->executeQuery(
            'SELECT * FROM product WHERE id IN (:ids)',
            ['ids' => $ids],
            ['ids' => ArrayParameterType::STRING]
        );
        $result = $q->fetchAllAssociative();
        $products = [];

        foreach ($result as $product) {
            $products[$product['id']] = new Product(
                $product['id'],
                $product['description'],
                (int)$product['category'],
                (float)$product['price']
            );
        }

        return $products;
    }
}
