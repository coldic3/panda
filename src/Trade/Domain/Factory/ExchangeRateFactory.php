<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRate;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;

final class ExchangeRateFactory implements ExchangeRateFactoryInterface
{
    public function create(AssetInterface $baseAsset, AssetInterface $quoteAsset, float $rate): ExchangeRateInterface
    {
        return new ExchangeRate($baseAsset, $quoteAsset, $rate);
    }
}
