<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRate;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindExchangeRatesQuery implements QueryInterface
{
    public function __construct(
        public ?string $baseTicker = null,
        public ?string $quoteTicker = null,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
