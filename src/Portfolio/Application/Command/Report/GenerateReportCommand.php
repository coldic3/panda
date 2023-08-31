<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Report;

use Symfony\Component\Uid\Uuid;

final readonly class GenerateReportCommand
{
    public function __construct(public Uuid $id)
    {
    }
}
