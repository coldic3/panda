<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\Symfony\Security;

use Panda\Account\Domain\Exception\AuthorizedUserNotFoundException;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final readonly class AuthorizedUserProvider implements AuthorizedUserProviderInterface
{
    public function __construct(private Security $security)
    {
    }

    public function provide(): OwnerInterface
    {
        Assert::nullOrIsInstanceOf(
            $user = $this->security->getUser(),
            UserInterface::class
        );

        if (null === $user) {
            throw new AuthorizedUserNotFoundException();
        }

        return $user;
    }
}
