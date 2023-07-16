<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\ExchangeRate;

use Panda\Core\Domain\Model\IdentifiableInterface;
use Panda\Core\Domain\Model\TimestampableInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;

interface ExchangeRateInterface extends IdentifiableInterface, TimestampableInterface
{
    public const RATE_PRECISION = 4;

    public function getBaseAsset(): AssetInterface;

    public function getQuoteAsset(): AssetInterface;

    public function getRate(): float;

    public function setRate(float $rate): void;
}
