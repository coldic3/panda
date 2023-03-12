<?php

namespace spec\App\Account\Application\Event;

use ApiPlatform\Api\IriConverterInterface;
use App\Account\Application\Event\AuthenticationSuccessEventListener;
use App\Account\Domain\Model\User;
use App\Account\Infrastructure\ApiResource\UserResource;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
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
