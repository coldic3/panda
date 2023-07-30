<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLog;

use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindExchangeRateLogQuery implements QueryInterface
{
    public function __construct(public Uuid $id)
    {
    }
}
