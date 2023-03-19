<?php

namespace spec\Panda\Account\Application\Query\User;

use Panda\Account\Application\Query\User\FindUserQuery;
use Panda\Account\Application\Query\User\FindUserQueryHandler;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

class FindUserQueryHandlerSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, Security $security)
    {
        $this->beConstructedWith($userRepository, $security);
    }

    function it_is_find_user_query_handler()
    {
        $this->shouldHaveType(FindUserQueryHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    function it_finds_user_by_id(
        UserRepositoryInterface $userRepository,
        Security $security,
        UserInterface $user,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $user->compare($authorizedUser)->willReturn(true);

        $security->getUser()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn($user);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn($user);
    }

    function it_returns_null_if_user_not_found(
        UserRepositoryInterface $userRepository,
        Security $security,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $security->getUser()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn(null);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn(null);
    }

    function it_returns_null_if_user_not_authorized(
        UserRepositoryInterface $userRepository,
        Security $security,
        Uuid $userId,
    ) {
        $security->getUser()->willReturn(null);

        $userRepository->findById($userId)->shouldNotBeCalled();

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn(null);
    }

    function it_returns_null_if_user_not_authorized_to_see_user(
        UserRepositoryInterface $userRepository,
        Security $security,
        UserInterface $user,
        UserInterface $authorizedUser,
        Uuid $userId,
    ) {
        $user->compare($authorizedUser)->willReturn(false);

        $security->getUser()->willReturn($authorizedUser);
        $userRepository->findById($userId)->willReturn($user);

        $this(new FindUserQuery($userId->getWrappedObject()))->shouldReturn(null);
    }
}
