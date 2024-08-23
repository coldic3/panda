<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\OpenApi\Filter;

use ApiPlatform\Metadata\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final class OperationAssetFilter implements FilterInterface
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'fromOperation.asset.id' => [
                'property' => 'fromOperation.asset.id',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
            'toOperation.asset.id' => [
                'property' => 'toOperation.asset.id',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
        ];
    }
}
