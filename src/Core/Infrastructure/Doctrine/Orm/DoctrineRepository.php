<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\Query\DoctrineQueryBuilder;
use Panda\Core\Infrastructure\Doctrine\Orm\Query\NullQuery;
use Webmozart\Assert\Assert;

abstract class DoctrineRepository implements RepositoryInterface
{
    /**
     * @param class-string $entityClass
     */
    public function __construct(
        protected EntityManagerInterface $em,
        protected string $entityClass,
        protected string $alias,
    ) {
    }

    public function collection(?QueryInterface $query = null): CollectionIteratorInterface
    {
        return (new DoctrineCollectionIterator($this->prepareQueryBuilder($query)))->withoutPagination();
    }

    public function pagination(?QueryInterface $query = null, ?int $page = null, ?int $itemsPerPage = null): CollectionIteratorInterface
    {
        Assert::notNull($page);
        Assert::notNull($itemsPerPage);

        return (new DoctrineCollectionIterator($this->prepareQueryBuilder($query)))->withPagination($page, $itemsPerPage);
    }

    public function item(?QueryInterface $query = null): ?object
    {
        $item = $this->prepareQueryBuilder($query)->limit(1)->getQuery()->getOneOrNullResult();

        if (null === $item) {
            return null;
        }

        Assert::isInstanceOf($item, $this->entityClass);

        return $item;
    }

    protected function prepareQueryBuilder(?QueryInterface $query = null): DoctrineQueryBuilder
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select($this->alias)
            ->from($this->entityClass, $this->alias);

        if (null === $query) {
            $query = new NullQuery();
        }

        $query->setQueryBuilder(new DoctrineQueryBuilder($queryBuilder));
        /** @var DoctrineQueryBuilder $queryBuilder */
        $queryBuilder = $query->buildQuery($this->alias);

        return $queryBuilder;
    }
}
