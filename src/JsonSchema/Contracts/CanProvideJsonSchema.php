<?php declare(strict_types=1);

namespace Cognesy\Utils\JsonSchema\Contracts;

/**
 * Response model can provide a JSON schema for the response object
 */
interface CanProvideJsonSchema
{
    public function toJsonSchema() : array;
}