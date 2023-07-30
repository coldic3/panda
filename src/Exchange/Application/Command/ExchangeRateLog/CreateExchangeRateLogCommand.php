<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLog;

use Panda\Core\Application\Command\CommandInterface;

final readonly class CreateExchangeRateLogCommand implements CommandInterface
{
    public function __construct(
        public string $baseTicker,
        public string $quoteTicker,
        public float $rate,
        public \DateTimeInterface $startedAt,
        public \DateTimeInterface $endedAt,
    ) {
    }
}
