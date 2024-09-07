<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\Symfony\Security;

use Panda\Account\Domain\Model\User as DomainUser;

final class User extends DomainUser implements UserInterface
{
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }
}
