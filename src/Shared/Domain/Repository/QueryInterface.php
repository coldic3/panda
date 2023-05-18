<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

use Doctrine\ORM\QueryBuilder;

interface QueryInterface
{
    public function buildQuery(string $alias): QueryBuilder;

    public function setQueryBuilder(QueryBuilder $queryBuilder): void;
}
