<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Orm;

use App\Shared\Domain\Repository\CollectionIteratorInterface;
use App\Shared\Domain\Repository\PaginatorInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Webmozart\Assert\Assert;

final class DoctrineCollectionIterator implements CollectionIteratorInterface
{
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    public function __construct(private QueryBuilder $queryBuilder)
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
        return $this->paginator()->count();
    }

    public function paginator(): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $firstResult = $this->page * $this->itemsPerPage;
        $maxResults = $this->itemsPerPage;

        $repository = $this->filter(static function (QueryBuilder $qb) use ($firstResult, $maxResults) {
            $qb->setFirstResult($firstResult)->setMaxResults($maxResults);
        });

        return new DoctrinePaginator(new Paginator($repository->queryBuilder->getQuery()));
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

    protected function __clone(): void
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
