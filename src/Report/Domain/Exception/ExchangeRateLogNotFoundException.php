<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Exception;

final class ExchangeRateLogNotFoundException extends \Exception
{
    protected $message = 'Exchange rate log not found.';
}
