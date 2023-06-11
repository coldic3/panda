<?php

declare(strict_types=1);

namespace Panda\Shared\Application\Event;

use Panda\Shared\Domain\Event\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): mixed;
}
