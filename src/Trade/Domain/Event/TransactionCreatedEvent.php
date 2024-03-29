<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Event;

use Panda\Core\Domain\Event\EventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class TransactionCreatedEvent implements EventInterface
{
    public function __construct(public Uuid $transactionId)
    {
    }
}
