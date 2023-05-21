<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\Listener;

use ApiPlatform\Api\IriConverterInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Infrastructure\ApiResource\UserResource;

final readonly class AuthenticationSuccessEventListener
{
    public function __construct(private IriConverterInterface $iriConverter)
    {
    }

    public function __invoke(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        /** @var UserInterface $user */
        $user = $event->getUser();
        $userResource = UserResource::fromModel($user);

        $data['user'] = $this->iriConverter->getIriFromResource($userResource);

        $event->setData($data);
    }
}
