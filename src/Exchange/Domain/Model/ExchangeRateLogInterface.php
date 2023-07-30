<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnershipInterface;

interface ExchangeRateLogInterface extends ExchangeRateInterface, OwnershipInterface
{
    public function getStartedAt(): \DateTimeInterface;

    public function getEndedAt(): \DateTimeInterface;
}
