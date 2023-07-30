<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLive;

use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindExchangeRateLiveQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
