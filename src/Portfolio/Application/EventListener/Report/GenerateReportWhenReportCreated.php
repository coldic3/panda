<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\EventListener\Report;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Application\Command\Report\GenerateReportCommand;
use Panda\Portfolio\Domain\Event\ReportCreatedEvent;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
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
