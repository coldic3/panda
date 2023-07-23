<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRate;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

/**
 * The distinction between {@see UpdateReversedExchangeRateCommand} and {@see UpdateExchangeRateCommand} is to avoid
 * infinite loop.
 *
 * When {@see UpdateReversedExchangeRateCommand} is dispatched, we don't want to create another reversed exchange rate
 * which will be in fact the same exchange rate as the first one.
 */
final readonly class UpdateReversedExchangeRateCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public ?float $rate = null,
    ) {
    }
}
