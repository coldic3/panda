<?php

namespace spec\Panda\Account\Application\Command\User;

use Panda\Account\Application\Command\User\CreateUserCommand;
use Panda\Account\Application\Command\User\CreateUserCommandHandler;
use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateUserCommandHandlerSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, UserFactoryInterface $userFactory)
    {
        $this->beConstructedWith($userRepository, $userFactory);
    }

    function it_is_create_user_command_handler()
    {
        $this->shouldHaveType(CreateUserCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_creates_new_user(
        UserRepositoryInterface $userRepository,
        UserFactoryInterface $userFactory,
        UserInterface $user,
    ) {
        $command = new CreateUserCommand('panda@example.com', 'I<3BambooShoots');

        $userRepository->findByEmail('panda@example.com')->willReturn(null);

        $userFactory->create('panda@example.com', 'I<3BambooShoots')
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $userRepository->save($user)->shouldBeCalledOnce();

        $this($command);
    }

    function it_does_nothing_if_user_already_created(
        UserRepositoryInterface $userRepository,
        UserFactoryInterface $userFactory,
        UserInterface $user,
    ) {
        $command = new CreateUserCommand('panda@example.com', 'I<3BambooShoots');

        $userRepository->findByEmail('panda@example.com')->willReturn($user);

        $userFactory->create(Argument::any())->shouldNotBeCalled();
        $userRepository->save(Argument::any())->shouldNotBeCalled();

        $this($command);
    }
}
