<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm\Query;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\SortDirectionEnum;

final readonly class DefaultTransactionQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(
        private OwnerInterface $owner,
        private ?string $fromOperationAssetId = null,
        private ?string $toOperationAssetId = null,
        private ?\DateTimeImmutable $afterConcludedAt = null,
        private ?\DateTimeImmutable $beforeConcludedAt = null,
    ) {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        $queryBuilder = $this->queryBuilder
            ->addSelect(
                'fromOperation',
                'fromOperationAsset',
                'toOperation',
                'toOperationAsset',
            )
            ->leftJoin($alias.'.fromOperation', 'fromOperation')
            ->leftJoin('fromOperation.asset', 'fromOperationAsset')
            ->leftJoin($alias.'.toOperation', 'toOperation')
            ->leftJoin('toOperation.asset', 'toOperationAsset')
            ->andWhere($alias.'.owner = :owner')
            ->addOrderBy($alias.'.concludedAt', SortDirectionEnum::DESC)
            ->setParameter('owner', $this->owner);

        if (null !== $this->fromOperationAssetId) {
            $queryBuilder = $queryBuilder
                ->andWhere('fromOperationAsset.id = :fromOperationAssetId')
                ->setParameter('fromOperationAssetId', $this->fromOperationAssetId);
        }

        if (null !== $this->toOperationAssetId) {
            $queryBuilder = $queryBuilder
                ->andWhere('toOperationAsset.id = :toOperationAssetId')
                ->setParameter('toOperationAssetId', $this->toOperationAssetId);
        }

        if (null !== $this->afterConcludedAt) {
            $queryBuilder = $queryBuilder
                ->andWhere($alias.'.concludedAt > :afterConcludedAt')
                ->setParameter(
                    'afterConcludedAt',
                    $this->afterConcludedAt->format(\DateTimeInterface::ATOM)
                );
        }

        if (null !== $this->beforeConcludedAt) {
            $queryBuilder = $queryBuilder
                ->andWhere($alias.'.concludedAt < :beforeConcludedAt')
                ->setParameter(
                    'beforeConcludedAt',
                    $this->beforeConcludedAt->format(\DateTimeInterface::ATOM)
                );
        }

        return $queryBuilder;
    }
}
