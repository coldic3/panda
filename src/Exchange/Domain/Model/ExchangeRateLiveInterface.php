<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnershipInterface;
use Panda\Core\Domain\Model\TimestampableInterface;

interface ExchangeRateLiveInterface extends ExchangeRateInterface, TimestampableInterface, OwnershipInterface
{
}
