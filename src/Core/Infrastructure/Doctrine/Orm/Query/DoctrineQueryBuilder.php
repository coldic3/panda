<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Doctrine\Orm\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

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

    public function innerJoin(string $join, string $alias): self
    {
        $this->queryBuilder->innerJoin($join, $alias);

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

    public function andNestedWhere(array $conditions): self
    {
        $this->queryBuilder->andWhere($this->complexConditionBuilder($conditions));

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

    private function complexConditionBuilder(array $conditions, string $operator = 'AND'): Composite
    {
        // Create a new expression
        $expr = $this->queryBuilder->expr();

        // This will hold the combined conditions
        $combined = [];

        foreach ($conditions as $key => $value) {
            if (is_string($key) && in_array(strtoupper($key), ['AND', 'OR'])) {
                // If the key is a string and is an operator (AND/OR)
                $nestedOperator = strtoupper($key);
                $combined[] = $this->complexConditionBuilder($value, $nestedOperator);
            } else {
                // It's a direct condition
                $combined[] = $value;
            }
        }

        // Combine the conditions based on the operator
        if ('AND' === $operator) {
            return call_user_func_array([$expr, 'andX'], $combined);
        } elseif ('OR' === $operator) {
            return call_user_func_array([$expr, 'orX'], $combined);
        } else {
            throw new \InvalidArgumentException('Invalid operator');
        }
    }
}
