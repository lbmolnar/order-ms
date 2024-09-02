<?php

declare(strict_types=1);

namespace App\Tests\Api\Order;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetDiscountsApiTest extends WebTestCase
{
    public function testGetDiscountsReturnsEmptyArray(): void
    {
        $client = static::createClient();
        $data = [
            'id' => '1',
            'customer-id' => '1',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '1',
                    'unit-price' => '12.00',
                    'total' => '120',
                ],
            ],
            'total' => '120'
        ];
        $client->request('POST', '/order/discount', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], json_encode($data));
        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        self::assertIsArray($responseData);
        self::assertArrayHasKey('data', $responseData);
        self::assertCount(0, $responseData['data']);
    }

    public function testGetAllDiscountTypes(): void
    {
        $client = static::createClient();
        $data = [
            'id' => '1',
            'customer-id' => '1',
            'items' => [
                [
                    'product-id' => 'A102',
                    'quantity' => '100',
                    'unit-price' => '49.5',
                    'total' => '4950',
                ],
                [
                    'product-id' => 'A101',
                    'quantity' => '2',
                    'unit-price' => '9.75',
                    'total' => '19.50',
                ],
                [
                    'product-id' => 'B101',
                    'quantity' => '1000',
                    'unit-price' => '4.99',
                    'total' => '4990',
                ],
            ],
            'total' => '9959.5'
        ];
        $client->request('POST', '/order/discount', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], json_encode($data));
        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        self::assertIsArray($responseData);
        self::assertArrayHasKey('data', $responseData);
        self::assertCount(3, $responseData['data']);
        $discount = array_shift($responseData['data']);
        self::assertEquals([
            'type' => 'extra_product',
            'id' => 'B101',
            'description' => 'Basic on-off switch',
            'category' => 2,
            'price' => '4.99',
            'quantity' => 1,
        ], $discount);
        $discount = array_shift($responseData['data']);
        self::assertEquals([
            'type' => 'discounted_product_line',
            'orderLineReplaced' => 1,
            'product-id' => 'A101',
            'quantity' => 2,
            'unit-price' => 9.75,
            'total' => 17.55,
        ], $discount);
        $discount = array_shift($responseData['data']);
        self::assertEquals([
            'type' => 'order_total_discount',
            'amount' => 995.76,
        ], $discount);
    }

    public function testGetDiscountsUnprocessableEntity(): void
    {
        $client = static::createClient();
        $client->request('POST', '/order/discount', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], 'dummy data');
        $response = $client->getResponse();

        self::assertEquals(422, $response->getStatusCode());
    }

    public function testGetDiscountsUnsupportedMediaType(): void
    {
        $client = static::createClient();
        $client->request('POST', '/order/discount', [], [], [], 'dummy data');
        $response = $client->getResponse();

        self::assertEquals(415, $response->getStatusCode());
    }

    public function testGetDiscountsBadRequest(): void
    {
        $client = static::createClient();
        $data = [
            'id' => '1',
            'customer-id' => '1',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '1',
                    'unit-price' => '12.00',
                    'total' => '120',
                ],
            ],
        ];
        $client->request('POST', '/order/discount', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], json_encode($data));
        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals('The required properties ({missing}) are missing', $responseData['error']);
        self::assertEquals(['missing' => ['total']], $responseData['args']);
    }
}
