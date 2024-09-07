<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\Symfony\Security;

use Panda\Account\Domain\Repository\UserRepositoryInterface as DomainUserRepositoryInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

interface UserRepositoryInterface extends DomainUserRepositoryInterface, PasswordUpgraderInterface
{
}
