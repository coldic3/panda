<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\Doctrine\Orm\Query;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

final readonly class DefaultExchangeRateLogQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(
        private OwnerInterface $owner,
        private ?string $baseTicker = null,
        private ?string $quoteTicker = null,
        private ?\DateTimeInterface $fromDatetime = null,
        private ?\DateTimeInterface $toDatetime = null,
    ) {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        $queryBuilder = $this->queryBuilder
            ->andWhere($alias.'.owner = :owner')
            ->setParameter('owner', $this->owner)
            ->addOrderBy($alias.'.startedAt', SortDirectionEnum::DESC);

        if (null !== $this->baseTicker) {
            $queryBuilder->andWhere($alias.'.baseTicker = :baseTicker');
            $queryBuilder->setParameter('baseTicker', $this->baseTicker);
        }

        if (null !== $this->quoteTicker) {
            $queryBuilder->andWhere($alias.'.quoteTicker = :quoteTicker');
            $queryBuilder->setParameter('quoteTicker', $this->quoteTicker);
        }

        if (null !== $this->fromDatetime) {
            $queryBuilder->andWhere($alias.'.startedAt >= :fromDatetime');
            $queryBuilder->setParameter('fromDatetime', $this->fromDatetime);
        }

        if (null !== $this->toDatetime) {
            $queryBuilder->andWhere($alias.'.endedAt <= :toDatetime');
            $queryBuilder->setParameter('toDatetime', $this->toDatetime);
        }

        return $queryBuilder;
    }
}
