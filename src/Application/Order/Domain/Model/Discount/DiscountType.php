<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model\Discount;

enum DiscountType: string
{
    case TOTAL = 'order_total_discount';
    case ORDER_LINE_REPLACEMENT = 'discounted_product_line';
    case EXTRA_PRODUCT = 'extra_product';
}
