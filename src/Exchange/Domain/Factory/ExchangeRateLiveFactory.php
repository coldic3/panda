<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;

final class ExchangeRateLiveFactory implements ExchangeRateLiveFactoryInterface
{
    public function create(string $baseTicker, string $quoteTicker, float $rate): ExchangeRateLiveInterface
    {
        return new ExchangeRateLive($baseTicker, $quoteTicker, $rate);
    }
}
