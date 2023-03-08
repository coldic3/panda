<?php

declare(strict_types=1);

namespace App\Account\Application\Query\User;

use App\Account\Domain\Model\UserInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final class FindUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function __invoke(FindUserQuery $query): ?UserInterface
    {
        return $this->repository->findById($query->id);
    }
}
