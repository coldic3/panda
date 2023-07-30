<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\OpenApi\Filter;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final class BaseQuoteResourcesFilter implements FilterInterface
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'baseTicker' => [
                'property' => 'baseTicker',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
            'quoteTicker' => [
                'property' => 'quoteTicker',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
        ];
    }
}
