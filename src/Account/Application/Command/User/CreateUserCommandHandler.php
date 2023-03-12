<?php

declare(strict_types=1);

namespace App\Account\Application\Command\User;

use App\Account\Domain\Factory\UserFactoryInterface;
use App\Account\Domain\Model\UserInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserFactoryInterface $userFactory,
    ) {
    }

    public function __invoke(CreateUserCommand $command): UserInterface
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (null !== $user) {
            return $user;
        }

        $user = $this->userFactory->create($command->email, $command->password);

        $this->userRepository->save($user);

        return $user;
    }
}
