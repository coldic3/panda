<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\Core\Domain\Model\TimestampableInterface;

interface ExchangeRateLiveInterface extends ExchangeRateInterface, TimestampableInterface
{
}
