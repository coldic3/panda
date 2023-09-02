<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ReportGenerator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Panda\Portfolio\Domain\Exception\InvalidReportEntryTypeException;
use Panda\Portfolio\Domain\Exception\ReportEntryConfigurationInvalidKeyTypeException;
use Panda\Portfolio\Domain\Exception\ReportEntryConfigurationMissingKeyException;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFile;
use Panda\Portfolio\Domain\ValueObject\ReportFileInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class PerformanceReportGenerator implements ReportGeneratorInterface
{
    public const TYPE = 'performance';
    private const NOT_APPLICABLE = 'N/A';
    private const RATE_TO_RETURN_PRECISION = 2;

    public function __construct(
        private string $projectDir,
        private TransactionRepositoryInterface $transactionRepository,
        private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository,
    ) {
    }

    public function generate(ReportInterface $report): ReportFileInterface
    {
        $portfolio = $report->getPortfolio();
        Assert::notNull(
            $owner = $report->getPortfolio()->getOwnedBy()
        );

        $reportFile = new ReportFile('local', sprintf('%s.csv', uniqid()));

        $reportEntry = $report->getEntry();

        if (self::TYPE !== $reportEntry->getType()) {
            throw new InvalidReportEntryTypeException($reportEntry->getType(), self::TYPE);
        }

        $configuration = $reportEntry->getConfiguration();
        $this->validateConfiguration($configuration);

        /** @phpstan-ignore-next-line fromDatetime configuration key is always string thanks to self::validateConfiguration */
        $fromDatetime = new \DateTimeImmutable($configuration['fromDatetime']);
        /** @phpstan-ignore-next-line toDatetime configuration key is always string thanks to self::validateConfiguration */
        $toDatetime = new \DateTimeImmutable($configuration['toDatetime']);

        /** @var CollectionIteratorInterface<TransactionInterface> $initialValueTransactions */
        $initialValueTransactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                beforeConcludedAt: $fromDatetime,
            )
        );

        $initialValueOfResources = $this->computeValueOfResources(
            $initialValueTransactions,
            $owner,
            $portfolio->getMainResource()->getTicker(),
            $fromDatetime,
        );

        /** @var CollectionIteratorInterface<TransactionInterface> $finalValueTransactions */
        $finalValueTransactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                afterConcludedAt: $fromDatetime,
                beforeConcludedAt: $toDatetime,
                afterConcludedAtInclusive: true,
                beforeConcludedAtInclusive: true,
            )
        );

        $finalValueOfResources = $initialValueOfResources + $this->computeValueOfResources(
            $finalValueTransactions,
            $owner,
            $portfolio->getMainResource()->getTicker(),
            $toDatetime,
        );

        $profitLoss = $finalValueOfResources - $initialValueOfResources;
        $rateToReturn = 0 === $initialValueOfResources
            ? self::NOT_APPLICABLE
            : sprintf(
                '%.2f%%',
                round($profitLoss / $initialValueOfResources * 100, self::RATE_TO_RETURN_PRECISION)
            );

        $csvFilePath = sprintf('%s/private/reports/%s', $this->projectDir, $reportFile->getFilename());

        $handle = fopen($csvFilePath, 'w');
        Assert::notFalse($handle);

        fputcsv($handle, ['initial value', 'final value', 'profit/loss', 'rate of return']);
        fputcsv($handle, [$initialValueOfResources, $finalValueOfResources, $profitLoss, $rateToReturn]);

        fclose($handle);

        return $reportFile;
    }

    /** @param array<string, mixed> $configuration */
    private function validateConfiguration(array $configuration): void
    {
        if (!isset($configuration['fromDatetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('fromDatetime');
        }

        if (!isset($configuration['toDatetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('toDatetime');
        }

        if (!is_string($configuration['fromDatetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('fromDatetime', 'string');
        }

        if (!is_string($configuration['toDatetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('toDatetime', 'string');
        }
    }

    /** @param iterable<TransactionInterface> $transactions */
    private function computeValueOfResources(
        iterable $transactions,
        OwnerInterface $owner,
        string $mainTicker,
        \DateTimeImmutable $datetime,
    ): int {
        $valueOfResources = .0;

        foreach ($transactions as $transaction) {
            $fromOperation = $transaction->getFromOperation();
            $toOperation = $transaction->getToOperation();
            $adjustmentOperations = $transaction->getAdjustmentOperations();

            if (null !== $fromOperation) {
                $ticker = $fromOperation->getAsset()->getTicker();

                if ($ticker === $mainTicker) {
                    $valueOfResources -= $fromOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $valueOfResources -= $fromOperation->getQuantity() * $exchangeRate->getRate();
                }
            }

            if (null !== $toOperation) {
                $ticker = $toOperation->getAsset()->getTicker();

                if ($ticker === $mainTicker) {
                    $valueOfResources += $toOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $valueOfResources += $toOperation->getQuantity() * $exchangeRate->getRate();
                }
            }

            foreach ($adjustmentOperations as $adjustmentOperation) {
                $ticker = $adjustmentOperation->getAsset()->getTicker();

                if ($ticker === $mainTicker) {
                    $valueOfResources -= $adjustmentOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $valueOfResources -= $adjustmentOperation->getQuantity() * $exchangeRate->getRate();
                }
            }
        }

        return (int) round($valueOfResources);
    }
}
