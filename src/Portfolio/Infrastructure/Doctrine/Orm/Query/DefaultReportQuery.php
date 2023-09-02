<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\Doctrine\Orm\Query;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryBuilderAwareTrait;
use Panda\Core\Domain\Repository\QueryBuilderInterface;
use Panda\Core\Domain\Repository\QueryInterface;

final readonly class DefaultReportQuery implements QueryInterface
{
    use QueryBuilderAwareTrait;

    public function __construct(private OwnerInterface $owner)
    {
    }

    public function buildQuery(string $alias): QueryBuilderInterface
    {
        return $this->queryBuilder
            ->addSelect('portfolio')
            ->leftJoin($alias.'.portfolio', 'portfolio')
            ->andWhere('portfolio.owner = :owner')
            ->setParameter('owner', $this->owner);
    }
}
