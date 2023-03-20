<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Shared\Application\Query\QueryInterface;

final class FindAssetsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $itemsPerPage = null,
    ) {
    }
}
