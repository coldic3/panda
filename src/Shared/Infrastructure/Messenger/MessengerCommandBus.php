<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Messenger;

use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Command\CommandInterface;
use Panda\Shared\Application\Exception\MessengerViolationFailedCompoundException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        try {
            return $this->handle($command, [new ValidationStamp(['panda'])]);
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
