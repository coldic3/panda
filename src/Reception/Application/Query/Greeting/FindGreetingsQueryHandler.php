<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Query\Greeting;

use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;

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
