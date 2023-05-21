<?php

declare(strict_types=1);

namespace Panda\Account\Application\Command\User;

use Panda\Shared\Application\Command\CommandInterface;

final readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
