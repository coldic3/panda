<?php

declare(strict_types=1);

namespace Panda\Core\Domain\Repository;

interface QueryInterface
{
    public function buildQuery(string $alias): QueryBuilderInterface;

    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): void;
}
