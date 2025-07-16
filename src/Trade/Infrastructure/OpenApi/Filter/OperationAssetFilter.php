<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\OpenApi\Filter;

use ApiPlatform\Metadata\FilterInterface;
use Symfony\Component\TypeInfo\TypeIdentifier;

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
                'type' => TypeIdentifier::STRING->value,
                'required' => false,
            ],
            'toOperation.asset.id' => [
                'property' => 'toOperation.asset.id',
                'type' => TypeIdentifier::STRING->value,
                'required' => false,
            ],
        ];
    }
}
