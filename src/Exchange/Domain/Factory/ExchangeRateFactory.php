<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;

final class ExchangeRateFactory implements ExchangeRateFactoryInterface
{
    public function create(string $baseTicker, string $quoteTicker, float $rate): ExchangeRateInterface
    {
        return new ExchangeRate($baseTicker, $quoteTicker, $rate);
    }
}
