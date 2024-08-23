<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;

interface ExchangeRateLogFactoryInterface
{
    public function create(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        \DateTimeInterface $startedAt,
        \DateTimeInterface $endedAt,
        ?OwnerInterface $owner = null,
    ): ExchangeRateLogInterface;
}
