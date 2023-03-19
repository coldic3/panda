<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Query\Greeting;

use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;

final class FindGreetingQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly GreetingRepositoryInterface $repository)
    {
    }

    public function __invoke(FindGreetingQuery $query): ?Greeting
    {
        return $this->repository->findById($query->id);
    }
}
