<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Report;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Portfolio\Application\Resolver\ReportGeneratorResolverInterface;
use Panda\Portfolio\Domain\Event\ReportGeneratedEvent;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class GenerateReportCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private ReportGeneratorResolverInterface $reportGeneratorResolver,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(GenerateReportCommand $command): ReportInterface
    {
        $report = $this->reportRepository->findById($command->id);
        Assert::notNull($report);

        $generator = $this->reportGeneratorResolver->resolve($report);
        $reportFile = $generator->generate($report);

        $report->setFile($reportFile);

        $this->reportRepository->save($report);

        $this->eventBus->dispatch(new ReportGeneratedEvent($report->getId()));

        return $report;
    }
}
