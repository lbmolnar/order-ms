<?php

declare(strict_types=1);

namespace App\Infrastructure\JSONSchemaValidator;

class OrderValidationSchema implements ValidationSchema
{
    private const string SUPPORTED_VALIDATION_PATH = '/order/discount';

    public static function supportsValidationPath(string $path): bool
    {
        return self::SUPPORTED_VALIDATION_PATH === $path;
    }

    public function getValidationSchema(): string
    {
        return <<<'JSON'
        {
            "$id": "https://example.com/schema.json",
            "type": "object",
            "properties": {
                "id": {
                    "type": "string",
                    "pattern": "^\\d+$"
                },
                "customer-id": {
                    "type": "string",
                    "pattern": "^\\d+$"
                },
                "total": {
                    "type": "string",
                    "pattern": "^([0-9]+([.][0-9]*)?|[.][0-9]+)$"
                },
                "items": {
                    "type": "array",
                    "items": {"$ref": "#/$defs/order-item"}
                }
            },
            "required": ["id", "customer-id", "total", "items"],
            "$defs": {
                "order-item": {
                    "type": "object",
                    "properties": {
                        "product-id": {
                            "type": "string"
                        },
                        "quantity": {
                            "type": "string",
                            "pattern": "^(?:[1-9]|\\d\\d\\d*)$"
                        },
                        "unit-price": {
                            "type": "string",
                            "pattern": "^([0-9]+([.][0-9]*)?|[.][0-9]+)$"
                        },
                        "total": {
                            "type": "string",
                            "pattern": "^([0-9]+([.][0-9]*)?|[.][0-9]+)$"
                        }
                    }
                }
            }
        }
        JSON;
    }
}
