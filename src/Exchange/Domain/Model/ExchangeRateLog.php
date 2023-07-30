<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

class ExchangeRateLog extends ExchangeRate implements ExchangeRateLogInterface
{
    public function __construct(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        private readonly \DateTimeInterface $startedAt,
        private readonly \DateTimeInterface $endedAt,
    ) {
        parent::__construct($baseTicker, $quoteTicker, $rate);
    }

    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getEndedAt(): \DateTimeInterface
    {
        return $this->endedAt;
    }
}
