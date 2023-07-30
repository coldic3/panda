<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use Panda\Core\Application\Command\CommandInterface;

final readonly class CreateExchangeRateLiveCommand implements CommandInterface
{
    public function __construct(
        public string $baseTicker,
        public string $quoteTicker,
        public float $rate,
    ) {
    }
}
