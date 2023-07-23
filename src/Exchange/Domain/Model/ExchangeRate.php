<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\Core\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class ExchangeRate implements ExchangeRateInterface
{
    use TimestampableTrait;

    private Uuid $id;

    public function __construct(
        private readonly string $baseResourceTicker,
        private readonly string $quoteResourceTicker,
        private float $rate,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBaseResourceTicker(): string
    {
        return $this->baseResourceTicker;
    }

    public function getQuoteResourceTicker(): string
    {
        return $this->quoteResourceTicker;
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
