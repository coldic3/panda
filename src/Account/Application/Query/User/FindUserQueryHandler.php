<?php

declare(strict_types=1);

namespace Panda\Account\Application\Query\User;

use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;

final readonly class FindUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindUserQuery $query): ?UserInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $user = $this->repository->findById($query->id);

        if (null !== $user && !$user->compare($authorizedUser)) {
            return null;
        }

        return $user;
    }
}
