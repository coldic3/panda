<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\ReportGenerator;

use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Portfolio\Application\Generator\ReportFileGeneratorInterface;
use Panda\Portfolio\Domain\Calculator\CalculateAllocationReportDataInterface;
use Panda\Portfolio\Domain\Dto\AllocationReportConfigurationDto;
use Panda\Portfolio\Domain\Exception\InvalidReportEntryTypeException;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFile;
use Panda\Portfolio\Domain\ValueObject\ReportFileInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class AllocationReportGenerator implements ReportGeneratorInterface
{
    public const TYPE = 'allocation';

    public function __construct(
        private ReportFileGeneratorInterface $reportFileGenerator,
        private CalculateAllocationReportDataInterface $calculateAllocationReportData,
        private TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    public function supports(ReportInterface $report): bool
    {
        return self::TYPE === $report->getEntry()->getType();
    }

    public function getColumns(): array
    {
        return ['ticker', 'quantity', 'value', 'share'];
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

        $configuration = AllocationReportConfigurationDto::fromArray($reportEntry->getConfiguration());

        /** @var CollectionIteratorInterface<TransactionInterface> $transactions */
        $transactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                beforeConcludedAt: $configuration->datetime,
                beforeConcludedAtInclusive: true,
            )
        );

        $reportData = $this->calculateAllocationReportData->calculate(
            $transactions,
            $owner,
            $portfolio,
            $configuration->datetime,
        );

        $rows = [];
        foreach ($reportData['resources'] as $ticker) {
            $rows[] = [
                $ticker,
                $reportData['quantities'][$ticker],
                $reportData['values'][$ticker],
                sprintf('%.2f%%', $reportData['share'][$ticker]),
            ];
        }

        $this->reportFileGenerator->generate(
            $reportFile,
            $this->getColumns(),
            $rows,
        );

        return $reportFile;
    }
}
