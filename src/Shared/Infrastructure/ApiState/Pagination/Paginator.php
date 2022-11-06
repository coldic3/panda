<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiState\Pagination;

use ApiPlatform\State\Pagination\PaginatorInterface;

/**
 * @template T of object
 *
 * @implements \IteratorAggregate<T>
 */
final class Paginator implements PaginatorInterface, \IteratorAggregate
{
    /**
     * @param iterable<T> $items
     */
    public function __construct(
        private readonly iterable $items,
        private readonly int $currentPage,
        private readonly int $itemsPerPage,
        private readonly int $lastPage,
        private readonly int $totalItems,
    ) {
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    public function getLastPage(): float
    {
        return (float) $this->lastPage;
    }

    public function getTotalItems(): float
    {
        return (float) $this->totalItems;
    }

    public function getCurrentPage(): float
    {
        return (float) $this->currentPage;
    }

    public function getItemsPerPage(): float
    {
        return (float) $this->itemsPerPage;
    }

    /**
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        /** @psalm-suppress InvalidArgument */
        return new \ArrayIterator($this->items);
    }
}
