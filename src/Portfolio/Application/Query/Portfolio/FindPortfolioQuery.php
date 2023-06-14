<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Portfolio;

use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindPortfolioQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
