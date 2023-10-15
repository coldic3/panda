<?php

declare(strict_types=1);

namespace Panda\Report\Application\ReportGenerator;

use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Portfolio\Domain\Exception\InvalidReportEntryTypeException;
use Panda\Report\Application\Generator\ReportFileGeneratorInterface;
use Panda\Report\Domain\Calculator\CalculatePerformanceReportDataInterface;
use Panda\Report\Domain\Dto\PerformanceReportConfigurationDto;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\ValueObject\ReportFile;
use Panda\Report\Domain\ValueObject\ReportFileInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class PerformanceReportGenerator implements ReportGeneratorInterface
{
    public const TYPE = 'performance';

    public function __construct(
        private ReportFileGeneratorInterface $reportFileGenerator,
        private CalculatePerformanceReportDataInterface $calculatePerformanceReportData,
        private TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    public function supports(ReportInterface $report): bool
    {
        return self::TYPE === $report->getEntry()->getType();
    }

    public function getColumns(): array
    {
        return ['initial value', 'final value', 'profit/loss', 'rate of return'];
    }

    public function generate(ReportInterface $report): ReportFileInterface
    {
        $portfolio = $report->getPortfolio();
        Assert::notNull(
            $owner = $report->getPortfolio()->getOwnedBy()
        );

        $reportFile = new ReportFile(ReportFile::LOCAL_STORAGE, sprintf('%s.csv', uniqid()));

        $reportEntry = $report->getEntry();

        if (self::TYPE !== $reportEntry->getType()) {
            throw new InvalidReportEntryTypeException($reportEntry->getType(), self::TYPE);
        }

        $configuration = PerformanceReportConfigurationDto::fromArray($reportEntry->getConfiguration());

        /** @var CollectionIteratorInterface<TransactionInterface> $initialValueTransactions */
        $initialValueTransactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                beforeConcludedAt: $configuration->fromDatetime,
            )
        );

        /** @var CollectionIteratorInterface<TransactionInterface> $finalValueTransactions */
        $finalValueTransactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                afterConcludedAt: $configuration->fromDatetime,
                beforeConcludedAt: $configuration->toDatetime,
                afterConcludedAtInclusive: true,
                beforeConcludedAtInclusive: true,
            )
        );

        $reportData = $this->calculatePerformanceReportData->calculate(
            $initialValueTransactions,
            $finalValueTransactions,
            $owner,
            $portfolio,
            $configuration,
        );

        $this->reportFileGenerator->generate(
            $reportFile,
            $this->getColumns(),
            [[
                $reportData['initialValueOfResources'],
                $reportData['finalValueOfResources'],
                $reportData['profitLoss'],
                $reportData['rateToReturn'],
            ]]
        );

        return $reportFile;
    }
}
