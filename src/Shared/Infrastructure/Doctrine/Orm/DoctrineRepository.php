<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Orm;

use App\Shared\Domain\Repository\CollectionIteratorInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

abstract class DoctrineRepository implements RepositoryInterface
{
    private QueryBuilder $queryBuilder;

    public function __construct(
        protected EntityManagerInterface $em,
        string $entityClass,
        string $alias,
    ) {
        $this->queryBuilder = $this->em->createQueryBuilder()
            ->select($alias)
            ->from($entityClass, $alias);
    }

    public function collection(): CollectionIteratorInterface
    {
        return (new DoctrineCollectionIterator($this->query()))->withoutPagination();
    }

    public function pagination(int $page = null, int $itemsPerPage = null): CollectionIteratorInterface
    {
        Assert::notNull($page);
        Assert::notNull($itemsPerPage);

        return (new DoctrineCollectionIterator($this->query()))->withPagination($page, $itemsPerPage);
    }

    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function query(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
