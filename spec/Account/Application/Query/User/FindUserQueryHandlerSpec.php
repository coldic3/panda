<?php

namespace spec\Panda\Account\Application\Query\User;

use Panda\Account\Application\Query\User\FindUserQuery;
use Panda\Account\Application\Query\User\FindUserQueryHandler;
use Panda\Account\Domain\Exception\AuthorizedUserNotFoundException;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class FindUserQueryHandlerSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, AuthorizedUserProviderInterface $authorizedUserProvider)
    {
        $this->beConstructedWith($userRepository, $authorizedUserProvider);
    }

    function it_is_find_user_query_handler()
    {
        $this->shouldHaveType(FindUserQueryHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    function it_finds_user_by_id(
        UserRepositoryInterface $userRepository,
        AuthorizedUserProviderInterface $authorizedUserProvider,
        UserInterface $user,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $user->compare($authorizedUser)->willReturn(true);

        $authorizedUserProvider->provide()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn($user);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn($user);
    }

    function it_returns_null_if_user_not_found(
        UserRepositoryInterface $userRepository,
        AuthorizedUserProviderInterface $authorizedUserProvider,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $authorizedUserProvider->provide()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn(null);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn(null);
    }

    function it_throws_an_exception_if_user_not_authorized(
        UserRepositoryInterface $userRepository,
        AuthorizedUserProviderInterface $authorizedUserProvider,
        Uuid $userId,
    ) {
        $authorizedUserProvider->provide()->willThrow(AuthorizedUserNotFoundException::class);

        $userRepository->findById($userId)->shouldNotBeCalled();

        $this->shouldThrow(AuthorizedUserNotFoundException::class)
            ->during('__invoke', [new FindUserQuery($userId->getWrappedObject())]);
    }

    function it_returns_null_if_user_not_authorized_to_see_user(
        UserRepositoryInterface $userRepository,
        AuthorizedUserProviderInterface $authorizedUserProvider,
        UserInterface $user,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $user->compare($authorizedUser)->willReturn(false);

        $authorizedUserProvider->provide()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn($user);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn(null);
    }
}
