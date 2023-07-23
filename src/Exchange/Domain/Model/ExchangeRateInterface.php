<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\Core\Domain\Model\IdentifiableInterface;
use Panda\Core\Domain\Model\TimestampableInterface;

interface ExchangeRateInterface extends IdentifiableInterface, TimestampableInterface
{
    public const RATE_PRECISION = 4;

    public function getBaseResourceTicker(): string;

    public function getQuoteResourceTicker(): string;

    public function getRate(): float;

    public function setRate(float $rate): void;
}
