<?php

declare(strict_types=1);

namespace Panda\Account\Application\Command\User;

use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

final readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserFactoryInterface $userFactory,
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
