<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\ExchangeRate;

use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindExchangeRateByAssetIdsQuery implements QueryInterface
{
    public function __construct(
        public Uuid $baseAssetId,
        public Uuid $quoteAssetId,
    ) {
    }
}
