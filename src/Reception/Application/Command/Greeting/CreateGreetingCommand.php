<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Command\Greeting;

use Panda\Shared\Application\Command\CommandInterface;

final class CreateGreetingCommand implements CommandInterface
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
