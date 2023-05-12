<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\OpenApi\Filter;

use ApiPlatform\Api\FilterInterface;

final class ConcludedAtFilter implements FilterInterface
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'concludedAt[after]' => [
                'property' => 'concludedAt[after]',
                'type' => \DateTimeInterface::class,
                'required' => false,
            ],
            'concludedAt[before]' => [
                'property' => 'concludedAt[before]',
                'type' => \DateTimeInterface::class,
                'required' => false,
            ],
        ];
    }
}
