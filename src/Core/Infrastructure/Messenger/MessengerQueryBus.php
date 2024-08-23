<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Messenger;

use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function ask(QueryInterface $query): mixed
    {
        try {
            return $this->handle($query, [new ValidationStamp(['panda'])]);
        } catch (HandlerFailedException $e) {
            /**
             * @phpstan-ignore-next-line
             */
            throw current($e->getWrappedExceptions());
        }
    }
}
