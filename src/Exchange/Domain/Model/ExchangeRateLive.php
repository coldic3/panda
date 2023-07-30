<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\Core\Domain\Model\TimestampableTrait;

class ExchangeRateLive extends ExchangeRate implements ExchangeRateLiveInterface
{
    use TimestampableTrait;
}
