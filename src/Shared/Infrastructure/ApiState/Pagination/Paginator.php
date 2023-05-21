<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\ApiState\Pagination;

use ApiPlatform\State\Pagination\PaginatorInterface;

/**
 * @template T of object
 *
 * @implements \IteratorAggregate<T>
 */
final readonly class Paginator implements PaginatorInterface, \IteratorAggregate
{
    /**
     * @param iterable<T> $items
     */
    public function __construct(
        private iterable $items,
        private int $currentPage,
        private int $itemsPerPage,
        private int $lastPage,
        private int $totalItems,
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
        /**
         * @psalm-suppress InvalidArgument
         *
         * @phpstan-ignore-next-line
         */
        return new \ArrayIterator($this->items);
    }
}
