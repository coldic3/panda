<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

interface QueryBuilderInterface
{
    public function addSelect(string ...$select): self;

    public function leftJoin(string $join, string $alias): self;

    public function andWhere(string $where): self;

    public function addOrderBy(string $sort, SortDirectionEnum $direction = SortDirectionEnum::ASC): self;

    public function setParameter(string $key, mixed $value): self;

    public function limit(int $limit): self;

    public function offset(int $offset): self;

    /** @return object depending on the implementation */
    public function getQuery(): object;
}
