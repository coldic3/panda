<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Shared\Domain\Repository\QueryInterface;
use Panda\Shared\Domain\Repository\RepositoryInterface;
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

    public function collection(QueryInterface $query = null): CollectionIteratorInterface
    {
        return (new DoctrineCollectionIterator($this->queryBuilder($query)))->withoutPagination();
    }

    public function pagination(QueryInterface $query = null, int $page = null, int $itemsPerPage = null): CollectionIteratorInterface
    {
        Assert::notNull($page);
        Assert::notNull($itemsPerPage);

        return (new DoctrineCollectionIterator($this->queryBuilder($query)))->withPagination($page, $itemsPerPage);
    }

    public function item(QueryInterface $query = null): ?object
    {
        $item = $this->queryBuilder($query)->setMaxResults(1)->getQuery()->getOneOrNullResult();

        if (null === $item) {
            return null;
        }

        Assert::isInstanceOf($item, $this->entityClass);

        return $item;
    }

    protected function queryBuilder(QueryInterface $query = null): QueryBuilder
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select($this->alias)
            ->from($this->entityClass, $this->alias);

        if (null !== $query) {
            $query->setQueryBuilder($queryBuilder);
            $queryBuilder = $query->buildQuery($this->alias);
        }

        return $queryBuilder;
    }
}
