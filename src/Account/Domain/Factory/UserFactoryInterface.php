<?php

declare(strict_types=1);

namespace App\Account\Domain\Factory;

use App\Account\Domain\Model\UserInterface;

interface UserFactoryInterface
{
    public function create(string $email, ?string $password = null): UserInterface;
}
