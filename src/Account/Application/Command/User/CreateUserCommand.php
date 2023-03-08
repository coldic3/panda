<?php

declare(strict_types=1);

namespace App\Account\Application\Command\User;

use App\Shared\Application\Command\CommandInterface;

final class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
