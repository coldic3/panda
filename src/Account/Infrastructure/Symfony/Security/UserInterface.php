<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\Symfony\Security;

use Panda\Account\Domain\Model\UserInterface as DomainUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends DomainUserInterface, SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
}
