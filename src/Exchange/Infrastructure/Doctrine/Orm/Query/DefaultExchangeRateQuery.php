<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\Doctrine\Orm\Query;

use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

final readonly class DefaultExchangeRateQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(
        private ?string $baseResourceTicker = null,
        private ?string $quoteResourceTicker = null,
    ) {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        $queryBuilder = $this->queryBuilder
            ->addOrderBy($alias.'.updatedAt', SortDirectionEnum::DESC);

        if (null !== $this->baseResourceTicker) {
            $queryBuilder->andWhere($alias.'.baseResourceTicker = :baseResourceTicker');
            $queryBuilder->setParameter('baseResourceTicker', $this->baseResourceTicker);
        }

        if (null !== $this->quoteResourceTicker) {
            $queryBuilder->andWhere($alias.'.quoteResourceTicker = :quoteResourceTicker');
            $queryBuilder->setParameter('quoteResourceTicker', $this->quoteResourceTicker);
        }

        return $queryBuilder;
    }
}
