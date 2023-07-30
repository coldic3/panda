<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

interface ExchangeRateLogInterface extends ExchangeRateInterface
{
    public function getStartedAt(): \DateTimeInterface;

    public function getEndedAt(): \DateTimeInterface;
}
