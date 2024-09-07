<?php

namespace spec\Panda\Account\Domain\Provider;

use Panda\Account\Domain\Exception\AuthorizedUserNotFoundException;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Infrastructure\Symfony\Security\AuthorizedUserProvider;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class AuthorizedUserProviderSpec extends ObjectBehavior
{
    function let(Security $security)
    {
        $this->beConstructedWith($security);
    }

    function it_is_authorized_user_provider()
    {
        $this->shouldHaveType(AuthorizedUserProvider::class);
        $this->shouldImplement(AuthorizedUserProviderInterface::class);
    }

    function it_asserts_that_user_is_instance_of_user_interface(Security $security, SymfonyUserInterface $user)
    {
        $security->getUser()->willReturn($user);

        $this->shouldThrow(\InvalidArgumentException::class)->during('provide');
    }

    function it_throws_exception_if_user_is_null(Security $security)
    {
        $security->getUser()->willReturn(null);

        $this->shouldThrow(AuthorizedUserNotFoundException::class)->during('provide');
    }

    function it_provides_authorized_user(Security $security, UserInterface $user)
    {
        $security->getUser()->willReturn($user);

        $this->provide()->shouldReturn($user);
    }
}
