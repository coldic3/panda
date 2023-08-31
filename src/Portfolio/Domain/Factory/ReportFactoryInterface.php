<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;

interface ReportFactoryInterface
{
    /** @param array<string, mixed> $entryConfiguration */
    public function create(
        string $name,
        string $entryType,
        array $entryConfiguration,
        PortfolioInterface $portfolio,
    ): ReportInterface;
}
