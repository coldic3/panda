<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Messenger;

use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

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
            return $this->handle($command);
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
