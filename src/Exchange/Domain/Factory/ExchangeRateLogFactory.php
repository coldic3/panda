<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;

final class ExchangeRateLogFactory implements ExchangeRateLogFactoryInterface
{
    public function create(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        \DateTimeInterface $startedAt,
        \DateTimeInterface $endedAt,
    ): ExchangeRateLogInterface {
        return new ExchangeRateLog($baseTicker, $quoteTicker, $rate, $startedAt, $endedAt);
    }
}
