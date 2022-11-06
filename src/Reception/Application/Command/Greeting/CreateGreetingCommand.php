<?php

declare(strict_types=1);

namespace App\Reception\Application\Command\Greeting;

use App\Shared\Application\Command\CommandInterface;

final class CreateGreetingCommand implements CommandInterface
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
