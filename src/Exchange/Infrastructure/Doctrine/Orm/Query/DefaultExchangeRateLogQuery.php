<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\Doctrine\Orm\Query;

use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

final readonly class DefaultExchangeRateLogQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(
        private ?string $baseTicker = null,
        private ?string $quoteTicker = null,
    ) {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        $queryBuilder = $this->queryBuilder
            ->addOrderBy($alias.'.startedAt', SortDirectionEnum::DESC);

        if (null !== $this->baseTicker) {
            $queryBuilder->andWhere($alias.'.baseTicker = :baseTicker');
            $queryBuilder->setParameter('baseTicker', $this->baseTicker);
        }

        if (null !== $this->quoteTicker) {
            $queryBuilder->andWhere($alias.'.quoteTicker = :quoteTicker');
            $queryBuilder->setParameter('quoteTicker', $this->quoteTicker);
        }

        return $queryBuilder;
    }
}
