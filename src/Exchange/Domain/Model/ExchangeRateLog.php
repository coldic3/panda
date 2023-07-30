<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;

class ExchangeRateLog extends ExchangeRate implements ExchangeRateLogInterface
{
    private ?OwnerInterface $owner = null;

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

    public function getOwnedBy(): ?OwnerInterface
    {
        return $this->owner;
    }

    public function setOwnedBy(OwnerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
