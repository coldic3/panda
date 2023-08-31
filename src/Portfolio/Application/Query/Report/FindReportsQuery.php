<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Report;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindReportsQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
