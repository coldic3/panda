<?php

declare(strict_types=1);

namespace App\Reception\Application\Query\Greeting;

use App\Reception\Domain\Model\Greeting;
use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

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
