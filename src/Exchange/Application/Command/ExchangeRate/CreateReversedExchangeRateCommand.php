<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRate;

use Panda\Core\Application\Command\CommandInterface;

/**
 * The distinction between {@see CreateReversedExchangeRateCommand} and {@see CreateExchangeRateCommand} is to avoid
 * infinite loop.
 *
 * When {@see CreateReversedExchangeRateCommand} is dispatched, we don't want to create another reversed exchange rate
 * which will be in fact the same exchange rate as the first one.
 */
final readonly class CreateReversedExchangeRateCommand implements CommandInterface
{
    public function __construct(
        public string $baseResourceTicker,
        public string $quoteResourceTicker,
        public float $rate,
    ) {
    }
}
