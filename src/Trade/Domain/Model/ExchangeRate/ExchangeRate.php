<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\ExchangeRate;

use Panda\Core\Domain\Model\TimestampableTrait;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Symfony\Component\Uid\Uuid;

class ExchangeRate implements ExchangeRateInterface
{
    use TimestampableTrait;

    private Uuid $id;

    public function __construct(
        private readonly AssetInterface $baseAsset,
        private readonly AssetInterface $quoteAsset,
        private float $rate,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBaseAsset(): AssetInterface
    {
        return $this->baseAsset;
    }

    public function getQuoteAsset(): AssetInterface
    {
        return $this->quoteAsset;
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
