<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Event;

use Panda\Core\Domain\Event\EventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ReversedExchangeLiveRateCreatedEvent implements EventInterface
{
    public function __construct(public Uuid $exchangeRateLiveId)
    {
    }
}
