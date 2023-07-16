<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\ExchangeRate;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

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
        public Uuid $baseAssetId,
        public Uuid $quoteAssetId,
        public float $rate,
    ) {
    }
}
