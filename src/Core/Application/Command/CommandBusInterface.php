<?php

declare(strict_types=1);

namespace Panda\Core\Application\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
