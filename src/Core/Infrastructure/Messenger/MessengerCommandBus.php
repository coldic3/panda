<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Messenger;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Command\CommandInterface;
use Panda\Core\Application\Exception\MessengerViolationFailedCompoundException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        try {
            return $this->handle($command, [new ValidationStamp(['panda'])]);
        } catch (HandlerFailedException $e) {
            /**
             * @phpstan-ignore-next-line
             */
            throw current($e->getWrappedExceptions());
        } catch (ValidationFailedException $e) {
            throw new MessengerViolationFailedCompoundException($e);
        }
    }
}
