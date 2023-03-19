<?php

declare(strict_types=1);

namespace Panda\Shared\Application\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
