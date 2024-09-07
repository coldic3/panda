<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Hasher;

use Panda\Account\Domain\Model\UserInterface;

interface UserPasswordHasherInterface
{
    public function hashPassword(UserInterface $user, #[\SensitiveParameter] string $plainPassword): string;
}
