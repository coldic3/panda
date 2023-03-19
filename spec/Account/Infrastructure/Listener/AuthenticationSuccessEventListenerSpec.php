<?php

namespace spec\Panda\Account\Infrastructure\Listener;

use ApiPlatform\Api\IriConverterInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Panda\Account\Domain\Model\User;
use Panda\Account\Infrastructure\ApiResource\UserResource;
use Panda\Account\Infrastructure\Listener\AuthenticationSuccessEventListener;
use PhpSpec\ObjectBehavior;

class AuthenticationSuccessEventListenerSpec extends ObjectBehavior
{
    function let(IriConverterInterface $iriConverter)
    {
        $this->beConstructedWith($iriConverter);
    }

    function it_is_authentication_success_event_listener()
    {
        $this->shouldHaveType(AuthenticationSuccessEventListener::class);
    }

    function it_appends_authenticated_user_iri_to_the_data(
        IriConverterInterface $iriConverter,
        AuthenticationSuccessEvent $event,
    ) {
        $user = new User('panda@example.com');
        $user->setPassword('secret');

        $event->getData()->willReturn(['token' => 'jwtTokenHere!']);
        $event->getUser()->willReturn($user);

        $iriConverter->getIriFromResource(UserResource::fromModel($user))
            ->willReturn(sprintf('/users/%s', $user->id));

        $event->setData([
            'token' => 'jwtTokenHere!',
            'user' => sprintf('/users/%s', $user->id),
        ])->shouldBeCalled();

        $this($event->getWrappedObject());
    }
}
