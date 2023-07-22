<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Factory;

use Panda\Account\Domain\Model\UserInterface;

interface UserFactoryInterface
{
    public function create(string $email, string $password = null): UserInterface;
}
