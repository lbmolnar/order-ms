<?php

declare(strict_types=1);

namespace App\Infrastructure\JSONSchemaValidator;

interface ValidationSchema
{
    public static function supportsValidationPath(string $path): bool;

    public function getValidationSchema(): string;
}
