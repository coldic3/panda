<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;

interface ExchangeRateLiveFactoryInterface
{
    public function create(string $baseTicker, string $quoteTicker, float $rate): ExchangeRateLiveInterface;
}
