<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Messenger;

use Panda\Shared\Application\Event\EventBusInterface;
use Panda\Shared\Domain\Event\EventInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerEventBus implements EventBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(EventInterface $event): mixed
    {
        try {
            return $this->handle($event, [new ValidationStamp(['panda'])]);
        } catch (HandlerFailedException $e) {
            /**
             * @psalm-suppress InvalidThrow
             *
             * @phpstan-ignore-next-line
             */
            throw current($e->getNestedExceptions());
        }
    }
}
