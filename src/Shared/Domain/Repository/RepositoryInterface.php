<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

interface RepositoryInterface
{
    public function collection(): CollectionIteratorInterface;

    public function pagination(int $page = null, int $itemsPerPage = null): CollectionIteratorInterface;
}
