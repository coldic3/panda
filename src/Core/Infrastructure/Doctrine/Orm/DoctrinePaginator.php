<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Panda\Core\Domain\Repository\PaginatorInterface;

final class DoctrinePaginator implements PaginatorInterface
{
    private int $firstResult;
    private int $maxResults;

    public function __construct(
        private readonly Paginator $paginator
    ) {
        $query = $paginator->getQuery();
        $firstResult = $query->getFirstResult();
        $maxResults = $query->getMaxResults();

        if (null === $firstResult || null === $maxResults) {
            throw new \InvalidArgumentException('Missing firstResult and maxResults from the query.');
        }

        $this->firstResult = $firstResult;
        $this->maxResults = $maxResults;
    }

    public function getItemsPerPage(): int
    {
        return $this->maxResults;
    }

    public function getCurrentPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) (1 + floor($this->firstResult / $this->maxResults));
    }

    public function getLastPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) (ceil($this->getTotalItems() / $this->maxResults) ?: 1);
    }

    public function getTotalItems(): int
    {
        return count($this->paginator);
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    public function getIterator(): \Traversable
    {
        return $this->paginator->getIterator();
    }
}
