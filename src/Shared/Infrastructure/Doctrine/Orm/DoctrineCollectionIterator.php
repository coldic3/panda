<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Shared\Domain\Repository\PaginatorInterface;
use Panda\Shared\Domain\Repository\QueryBuilderInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\Query\DoctrineQueryBuilder;
use Webmozart\Assert\Assert;

final class DoctrineCollectionIterator implements CollectionIteratorInterface
{
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    public function __construct(private DoctrineQueryBuilder $queryBuilder)
    {
    }

    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            yield from $paginator;

            return;
        }

        yield from $this->queryBuilder->getQuery()->getResult();
    }

    public function count(): int
    {
        Assert::notNull(
            $paginator = $this->paginator()
        );

        return $paginator->count();
    }

    public function paginator(): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $firstResult = $this->page * $this->itemsPerPage;
        $maxResults = $this->itemsPerPage;

        $repository = $this->filter(static function (QueryBuilderInterface $qb) use ($firstResult, $maxResults) {
            $qb->offset($firstResult)->limit($maxResults);
        });

        $query = $repository->queryBuilder->getQuery();

        return new DoctrinePaginator(new Paginator($query));
    }

    public function withoutPagination(): static
    {
        $cloned = clone $this;
        $cloned->page = null;
        $cloned->itemsPerPage = null;

        return $cloned;
    }

    public function withPagination(int $page, int $itemsPerPage): static
    {
        Assert::positiveInteger($page);
        Assert::positiveInteger($itemsPerPage);

        $cloned = clone $this;
        $cloned->page = $page - 1;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    private function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    private function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
