<?php

declare(strict_types=1);

namespace Panda\Report\Application\Command\Report;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Report\Domain\Event\ReportCreatedEvent;
use Panda\Report\Domain\Factory\ReportFactoryInterface;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CreateReportCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private ReportFactoryInterface $reportFactory,
        private ValidatorInterface $validator,
        private PortfolioRepositoryInterface $portfolioRepository,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateReportCommand $command): ReportInterface
    {
        $portfolio = $this->portfolioRepository->findById($command->portfolioId);
        Assert::notNull($portfolio);

        $report = $this->reportFactory->create(
            $command->name,
            $command->entryType,
            $command->entryConfiguration,
            $portfolio,
        );

        $this->validator->validate($report, ['groups' => ['panda:create']]);

        $this->reportRepository->save($report);

        $this->eventBus->dispatch(new ReportCreatedEvent($report->getId()));

        return $report;
    }
}
