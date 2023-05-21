<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Doctrine\Orm\Query;

use Panda\Shared\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Shared\Domain\Repository\QueryBuilderInterface;
use Panda\Shared\Domain\Repository\QueryInterface;

final readonly class NullQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        return $this->queryBuilder;
    }
}
