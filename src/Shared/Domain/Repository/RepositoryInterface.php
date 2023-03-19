<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

interface RepositoryInterface
{
    public function collection(): CollectionIteratorInterface;

    public function pagination(int $page = null, int $itemsPerPage = null): CollectionIteratorInterface;
}
