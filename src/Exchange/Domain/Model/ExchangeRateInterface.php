<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\Core\Domain\Model\IdentifiableInterface;

interface ExchangeRateInterface extends IdentifiableInterface
{
    public const RATE_PRECISION = 4;

    public function getBaseTicker(): string;

    public function getQuoteTicker(): string;

    public function getRate(): float;

    public function setRate(float $rate): void;
}
