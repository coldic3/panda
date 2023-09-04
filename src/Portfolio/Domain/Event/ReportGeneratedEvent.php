<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Event;

use Panda\Core\Domain\Event\EventInterface;
use Symfony\Component\Uid\Uuid;

final class ReportGeneratedEvent implements EventInterface
{
    public function __construct(public Uuid $reportId)
    {
    }
}
