<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

/**
 * The distinction between {@see UpdateReversedExchangeRateLiveCommand} and {@see UpdateExchangeRateLiveCommand} is to avoid
 * infinite loop.
 *
 * When {@see UpdateReversedExchangeRateLiveCommand} is dispatched, we don't want to create another reversed exchange rate
 * which will be in fact the same exchange rate as the first one.
 */
final readonly class UpdateReversedExchangeRateLiveCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public ?float $rate = null,
    ) {
    }
}
