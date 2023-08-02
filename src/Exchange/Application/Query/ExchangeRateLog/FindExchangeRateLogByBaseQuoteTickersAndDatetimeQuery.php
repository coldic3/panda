<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLog;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery implements QueryInterface
{
    public function __construct(
        public string $baseTicker,
        public string $quoteTicker,
        public \DateTimeInterface $datetime,
    ) {
    }
}
