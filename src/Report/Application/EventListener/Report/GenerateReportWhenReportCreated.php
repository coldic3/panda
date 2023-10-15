<?php

declare(strict_types=1);

namespace Panda\Report\Application\EventListener\Report;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Report\Application\Command\Report\GenerateReportCommand;
use Panda\Report\Domain\Event\ReportCreatedEvent;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class GenerateReportWhenReportCreated
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(ReportCreatedEvent $event): void
    {
        Assert::isInstanceOf(
            $report = $this->reportRepository->findById($event->reportId),
            ReportInterface::class
        );

        $report->setStartedAt(new \DateTimeImmutable());

        $this->reportRepository->save($report);

        $this->commandBus->dispatch(new GenerateReportCommand($event->reportId));
    }
}
