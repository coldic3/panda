<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindAssetsQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
