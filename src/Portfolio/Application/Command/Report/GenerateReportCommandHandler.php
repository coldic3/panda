<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Report;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class GenerateReportCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ReportRepositoryInterface $reportRepository)
    {
    }

    public function __invoke(GenerateReportCommand $command): ReportInterface
    {
        $report = $this->reportRepository->findById($command->id);
        Assert::notNull($report);

        // TODO: Generate report
        // 1. Call for report generator resolver
        // 2. Generate report
        // 3. Save report and add ReportFile to Report
        // 4. Dispatch report generated event
        // 5. Return report

        return $report;
    }
}
