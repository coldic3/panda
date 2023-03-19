<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Messenger;

use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function ask(QueryInterface $query): mixed
    {
        try {
            return $this->handle($query);
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
