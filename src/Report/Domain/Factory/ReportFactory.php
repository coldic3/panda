<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\Model\Report\Report;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\ValueObject\ReportEntry;

final readonly class ReportFactory implements ReportFactoryInterface
{
    public function create(
        string $name,
        string $entryType,
        array $entryConfiguration,
        PortfolioInterface $portfolio,
    ): ReportInterface {
        return new Report(
            $name,
            new ReportEntry($entryType, $entryConfiguration),
            $portfolio,
        );
    }
}
