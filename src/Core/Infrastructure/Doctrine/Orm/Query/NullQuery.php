<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Doctrine\Orm\Query;

use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;

final readonly class NullQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        return $this->queryBuilder;
    }
}
