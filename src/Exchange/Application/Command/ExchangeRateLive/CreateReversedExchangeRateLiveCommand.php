<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use Panda\Core\Application\Command\CommandInterface;

/**
 * The distinction between {@see CreateReversedExchangeRateLiveCommand} and {@see CreateExchangeRateLiveCommand} is to avoid
 * infinite loop.
 *
 * When {@see CreateReversedExchangeRateLiveCommand} is dispatched, we don't want to create another reversed exchange rate
 * which will be in fact the same exchange rate as the first one.
 */
final readonly class CreateReversedExchangeRateLiveCommand implements CommandInterface
{
    public function __construct(
        public string $baseTicker,
        public string $quoteTicker,
        public float $rate,
    ) {
    }
}
