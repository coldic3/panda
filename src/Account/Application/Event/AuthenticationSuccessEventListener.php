<?php

declare(strict_types=1);

namespace App\Account\Application\Event;

use ApiPlatform\Api\IriConverterInterface;
use App\Account\Domain\Model\UserInterface;
use App\Account\Infrastructure\ApiResource\UserResource;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

final class AuthenticationSuccessEventListener
{
    public function __construct(private readonly IriConverterInterface $iriConverter)
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
