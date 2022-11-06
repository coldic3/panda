<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

/**
 * @template T of object
 *
 * @implements \IteratorAggregate<T>
 */
interface CollectionIteratorInterface extends \IteratorAggregate, \Countable
{
    /** @return \Iterator<T> */
    public function getIterator(): \Iterator;

    public function count(): int;

    public function paginator(): ?PaginatorInterface;

    public function withPagination(int $page, int $itemsPerPage): static;

    public function withoutPagination(): static;
}
