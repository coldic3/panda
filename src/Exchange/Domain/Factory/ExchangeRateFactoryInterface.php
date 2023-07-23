<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\Exchange\Domain\Model\ExchangeRateInterface;

interface ExchangeRateFactoryInterface
{
    public function create(string $baseResourceTicker, string $quoteResourceTicker, float $rate): ExchangeRateInterface;
}
