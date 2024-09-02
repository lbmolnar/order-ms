<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Order\Application\CreateDiscounts;

use App\Application\Order\Application\CreateDiscounts\CreateDiscountsCommand;
use App\Application\Order\Application\CreateDiscounts\CreateDiscountsCommandHandler;
use App\Application\Order\Domain\Model\Discount\Discount;
use App\Application\Order\Domain\Model\Product;
use App\Application\Order\Domain\Service\DiscountCalculationRule;
use App\Application\Order\Domain\Service\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateDiscountsCommandHandlerTest extends TestCase
{
    private DiscountCalculationRule|MockObject $discountCalculationRuleMock;
    private ProductRepository|MockObject $productRepositoryMock;

    private CreateDiscountsCommandHandler $subject;

    public function testHandleSuccess(): void
    {
        $discountMock = $this->createMock(Discount::class);
        $product = new Product('C101', 'Winter tire 4 set', 3, 12.00);
        $command = new CreateDiscountsCommand(
            1,
            2,
            120,
            [
                ['product-id' => 'C101', 'quantity' => 10, 'unit-price' => 12, 'total' => 120]
            ]
        );

        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getProductsByIds')
            ->with(['C101'])
            ->willReturn(['C101' => $product]);
        $this->discountCalculationRuleMock
            ->expects(self::once())
            ->method('calculateDiscounts')
            ->willReturn([$discountMock]);

        $result = $this->subject->handle($command);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertEquals($discountMock, array_shift($result));
    }

    public function testHandleThrowsUncaughtException(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Something went wrong.');

        $command = new CreateDiscountsCommand(
            1,
            2,
            120,
            [
                ['product-id' => 'C101', 'quantity' => 10, 'unit-price' => 12, 'total' => 120]
            ]
        );

        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getProductsByIds')
            ->with(['C101'])
            ->willThrowException(new \RuntimeException('Something went wrong.'));

        $this->subject->handle($command);
    }

    protected function setUp(): void
    {
        $this->discountCalculationRuleMock = $this->createMock(DiscountCalculationRule::class);
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->subject = new CreateDiscountsCommandHandler(
            $this->discountCalculationRuleMock,
            $this->productRepositoryMock
        );
    }
}
