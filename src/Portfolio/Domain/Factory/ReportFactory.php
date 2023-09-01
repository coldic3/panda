<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Report\Report;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ValueObject\ReportEntry;

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
