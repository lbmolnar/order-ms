<?php

declare(strict_types=1);

namespace App\Infrastructure\JSONSchemaValidator;

use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator as OpisValidator;

class Validator
{
    /** @var array<string, ValidationSchema> */
    private array $validationSchemas;

    public function __construct(private readonly OpisValidator $validator, iterable $validationSchemas)
    {
        $this->validationSchemas = iterator_to_array($validationSchemas);
    }

    public function validate(string $path, string $data): array
    {
        $compatibleValidationSchema = null;
        foreach ($this->validationSchemas as $validationSchema) {
            if ($validationSchema->supportsValidationPath($path)) {
                $compatibleValidationSchema = $validationSchema;
                break;
            }
        }

        if (null === $compatibleValidationSchema) {
            throw new \RuntimeException('Validation schema not found');
        }

        $validationResult = $this->validator->validate(
            json_decode($data),
            $compatibleValidationSchema->getValidationSchema()
        );

        return true === $validationResult->isValid() ? [] : $this->parseValidationResult($validationResult);
    }

    private function parseValidationResult(ValidationResult $validationResult): array
    {
        return $this->parseValidationError($validationResult->error());
    }

    private function parseValidationError(ValidationError $error): array
    {
        $subErrors = [];
        foreach ($error->subErrors() as $subError) {
            $subErrors[] = $this->parseValidationError($subError);
        }

        return [
            'error' => $error->message(),
            'type' => $error->data()->type(),
            'args' => $error->args(),
            'subErrors' => $subErrors,
        ];
    }
}
