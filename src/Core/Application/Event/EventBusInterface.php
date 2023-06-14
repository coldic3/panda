<?php

declare(strict_types=1);

namespace Panda\Core\Application\Event;

use Panda\Core\Domain\Event\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
