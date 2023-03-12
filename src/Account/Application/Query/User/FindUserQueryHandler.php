<?php

declare(strict_types=1);

namespace App\Account\Application\Query\User;

use App\Account\Domain\Model\UserInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class FindUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(FindUserQuery $query): ?UserInterface
    {
        /** @var UserInterface|null $authorizedUser */
        $authorizedUser = $this->security->getUser();

        if (null === $authorizedUser) {
            return null;
        }

        $user = $this->repository->findById($query->id);

        if (null !== $user && !$user->compare($authorizedUser)) {
            return null;
        }

        return $user;
    }
}
