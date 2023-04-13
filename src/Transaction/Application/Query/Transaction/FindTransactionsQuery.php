<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Query\Transaction;

use Panda\Shared\Application\Query\QueryInterface;

final readonly class FindTransactionsQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
