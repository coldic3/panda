<?php

declare(strict_types=1);

namespace Panda\Core\Domain\Repository;

interface RepositoryInterface
{
    public function collection(QueryInterface $query = null): CollectionIteratorInterface;

    public function pagination(QueryInterface $query = null, int $page = null, int $itemsPerPage = null): CollectionIteratorInterface;

    public function item(QueryInterface $query = null): ?object;
}
