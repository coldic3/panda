<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Doctrine\Orm\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Panda\Shared\Domain\Repository\QueryBuilderInterface;
use Panda\Shared\Domain\Repository\SortDirectionEnum;

final readonly class DoctrineQueryBuilder implements QueryBuilderInterface
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function addSelect(string ...$select): self
    {
        $this->queryBuilder->addSelect(...$select);

        return $this;
    }

    public function leftJoin(string $join, string $alias): self
    {
        $this->queryBuilder->leftJoin($join, $alias);

        return $this;
    }

    public function andWhere(string $where): self
    {
        $this->queryBuilder->andWhere($where);

        return $this;
    }

    public function addOrderBy(string $sort, SortDirectionEnum $direction = SortDirectionEnum::ASC): self
    {
        $this->queryBuilder->addOrderBy($sort, $direction->value);

        return $this;
    }

    public function setParameter(string $key, mixed $value): self
    {
        $this->queryBuilder->setParameter($key, $value);

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->queryBuilder->setMaxResults($limit);

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->queryBuilder->setFirstResult($offset);

        return $this;
    }

    public function getQuery(): Query
    {
        return $this->queryBuilder->getQuery();
    }
}
