<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Portfolio;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindPortfoliosQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
