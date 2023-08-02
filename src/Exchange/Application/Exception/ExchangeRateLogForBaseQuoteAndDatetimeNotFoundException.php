<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Exception;

final class ExchangeRateLogForBaseQuoteAndDatetimeNotFoundException extends \Exception
{
    public function __construct(string $baseTicker, string $quoteTicker, \DateTimeInterface $datetime)
    {
        parent::__construct(sprintf(
            'Exchange rate log for pair "%s/%s" in "%s" not found.',
            $baseTicker,
            $quoteTicker,
            $datetime->format('Y-m-d H:i:s'),
        ));
    }
}
