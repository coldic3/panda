<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Command\Greeting;

use Panda\Shared\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final class UpdateGreetingCommand implements CommandInterface
{
    public function __construct(
        public readonly Uuid $id,
        public readonly ?string $name = null,
    ) {
    }
}
