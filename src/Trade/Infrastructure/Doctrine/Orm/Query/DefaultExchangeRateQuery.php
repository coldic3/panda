<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm\Query;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

final readonly class DefaultExchangeRateQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(
        private OwnerInterface $owner,
        private ?string $ticker = null,
    ) {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        $queryBuilder = $this->queryBuilder
            ->addSelect('baseAsset', 'quoteAsset')
            ->innerJoin($alias.'.baseAsset', 'baseAsset')
            ->innerJoin($alias.'.quoteAsset', 'quoteAsset')
            ->andWhere('baseAsset.owner = :owner')
            ->addOrderBy($alias.'.updatedAt', SortDirectionEnum::DESC)
            ->setParameter('owner', $this->owner);

        if (null !== $this->ticker) {
            $queryBuilder
                ->andWhere('baseAsset.ticker = :ticker OR quoteAsset.ticker = :ticker')
                ->setParameter('ticker', $this->ticker);
        }

        return $queryBuilder;
    }
}
