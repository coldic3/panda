<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

use Doctrine\ORM\QueryBuilder;

trait QueryBuilderAwareTrait
{
    protected readonly QueryBuilder $queryBuilder;

    public function setQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }
}
