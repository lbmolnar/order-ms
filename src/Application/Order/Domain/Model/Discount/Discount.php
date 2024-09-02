<?php

declare(strict_types=1);

namespace App\Application\Order\Domain\Model\Discount;

use JsonSerializable;

class Discount implements JsonSerializable
{
    public function __construct(
        private readonly DiscountType $type,
        private readonly DiscountObject $discountObject,
    ) {
    }

    public function getType(): DiscountType
    {
        return $this->type;
    }

    public function getDiscountObject(): DiscountObject
    {
        return $this->discountObject;
    }

    public function jsonSerialize(): array
    {
        return [
                'type' => $this->type->value,
            ]
            + $this->discountObject->jsonSerialize();
    }
}
