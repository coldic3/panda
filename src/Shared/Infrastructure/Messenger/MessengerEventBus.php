<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Messenger;

use Panda\Shared\Application\Event\EventBusInterface;
use Panda\Shared\Application\Exception\MessengerViolationFailedCompoundException;
use Panda\Shared\Domain\Event\EventInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MessengerEventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        try {
            $this->eventBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            /**
             * @psalm-suppress InvalidThrow
             *
             * @phpstan-ignore-next-line
             */
            throw current($e->getNestedExceptions());
        } catch (ValidationFailedException $e) {
            throw new MessengerViolationFailedCompoundException($e);
        }
    }
}
