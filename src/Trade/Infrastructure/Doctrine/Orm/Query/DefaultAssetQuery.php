<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm\Query;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Shared\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Shared\Domain\Repository\QueryBuilderInterface;
use Panda\Shared\Domain\Repository\QueryInterface;
use Panda\Shared\Domain\Repository\SortDirectionEnum;

final readonly class DefaultAssetQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(private OwnerInterface $owner)
    {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        return $this->queryBuilder
            ->andWhere($alias.'.owner = :owner')
            ->addOrderBy($alias.'.createdAt', SortDirectionEnum::DESC)
            ->setParameter('owner', $this->owner);
    }
}
