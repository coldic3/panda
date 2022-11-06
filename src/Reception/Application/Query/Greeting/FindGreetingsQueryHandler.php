<?php

declare(strict_types=1);

namespace App\Reception\Application\Query\Greeting;

use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Repository\CollectionIteratorInterface;

final class FindGreetingsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly GreetingRepositoryInterface $repository)
    {
    }

    public function __invoke(FindGreetingsQuery $query): ?CollectionIteratorInterface
    {
        $greetingRepository = $this->repository;

        if (null !== $query->name) {
            $greetingRepository = $greetingRepository->likeName($query->name);
        }

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $greetingRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $greetingRepository->collection();
    }
}
