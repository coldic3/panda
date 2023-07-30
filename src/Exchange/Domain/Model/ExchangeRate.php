<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Symfony\Component\Uid\Uuid;

class ExchangeRate implements ExchangeRateInterface
{
    private Uuid $id;

    public function __construct(
        private readonly string $baseTicker,
        private readonly string $quoteTicker,
        private float $rate,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBaseTicker(): string
    {
        return $this->baseTicker;
    }

    public function getQuoteTicker(): string
    {
        return $this->quoteTicker;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }
}
