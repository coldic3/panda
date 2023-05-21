<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

trait QueryBuilderAwareTrait
{
    protected readonly QueryBuilderInterface $queryBuilder;

    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }
}
