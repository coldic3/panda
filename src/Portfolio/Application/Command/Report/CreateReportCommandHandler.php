<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Report;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Factory\ReportFactoryInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CreateReportCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private ReportFactoryInterface $reportFactory,
        private ValidatorInterface $validator,
        private PortfolioRepositoryInterface $portfolioRepository,
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

        return $report;
    }
}
