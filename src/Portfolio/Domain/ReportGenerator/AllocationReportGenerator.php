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

final readonly class AllocationReportGenerator implements ReportGeneratorInterface
{
    public const TYPE = 'allocation';
    private const SHARE_PRECISION = 2;

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

        /** @phpstan-ignore-next-line datetime configuration key is always string thanks to self::validateConfiguration */
        $datetime = new \DateTimeImmutable($configuration['datetime']);

        /** @var CollectionIteratorInterface<TransactionInterface> $transactions */
        $transactions = $this->transactionRepository->collection(
            $this->transactionRepository->defaultQuery(
                owner: $owner,
                beforeConcludedAt: $datetime,
                beforeConcludedAtInclusive: true,
            )
        );

        $resourceData = $this->computeResourceData(
            $transactions,
            $owner,
            $portfolio->getMainResource()->getTicker(),
            $datetime,
        );

        $csvFilePath = sprintf('%s/private/reports/%s', $this->projectDir, $reportFile->getFilename());

        $handle = fopen($csvFilePath, 'w');
        Assert::notFalse($handle);

        fputcsv($handle, ['ticker', 'quantity', 'value', 'share']);

        foreach ($resourceData['resources'] as $ticker) {
            fputcsv($handle, [
                $ticker,
                $resourceData['quantities'][$ticker],
                $resourceData['values'][$ticker],
                sprintf('%.2f%%', $resourceData['share'][$ticker]),
            ]);
        }

        fclose($handle);

        return $reportFile;
    }

    /** @param array<string, mixed> $configuration */
    private function validateConfiguration(array $configuration): void
    {
        if (!isset($configuration['datetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('datetime');
        }

        if (!is_string($configuration['datetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('datetime', 'string');
        }
    }

    /**
     * @param iterable<TransactionInterface> $transactions
     *
     * @return array<string, array<string, int|float>|string[]>
     */
    private function computeResourceData(
        iterable $transactions,
        OwnerInterface $owner,
        string $mainTicker,
        \DateTimeImmutable $datetime,
    ): array {
        $resourceQuantities = [];
        $resourceValues = [];

        foreach ($transactions as $transaction) {
            $fromOperation = $transaction->getFromOperation();
            $toOperation = $transaction->getToOperation();
            $adjustmentOperations = $transaction->getAdjustmentOperations();

            if (null !== $fromOperation) {
                $ticker = $fromOperation->getAsset()->getTicker();

                if (!isset($resourceQuantities[$ticker])) {
                    $resourceQuantities[$ticker] = 0;
                }

                if (!isset($resourceValues[$ticker])) {
                    $resourceValues[$ticker] = 0;
                }

                if ($ticker === $mainTicker) {
                    $resourceQuantities[$ticker] -= $fromOperation->getQuantity();
                    $resourceValues[$ticker] -= $fromOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $resourceQuantities[$ticker] -= $fromOperation->getQuantity();
                    $resourceValues[$ticker] -= $fromOperation->getQuantity() * $exchangeRate->getRate();
                }
            }

            if (null !== $toOperation) {
                $ticker = $toOperation->getAsset()->getTicker();

                if (!isset($resourceQuantities[$ticker])) {
                    $resourceQuantities[$ticker] = 0;
                }

                if (!isset($resourceValues[$ticker])) {
                    $resourceValues[$ticker] = 0;
                }

                if ($ticker === $mainTicker) {
                    $resourceQuantities[$ticker] += $toOperation->getQuantity();
                    $resourceValues[$ticker] += $toOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $resourceQuantities[$ticker] += $toOperation->getQuantity();
                    $resourceValues[$ticker] += $toOperation->getQuantity() * $exchangeRate->getRate();
                }
            }

            foreach ($adjustmentOperations as $adjustmentOperation) {
                $ticker = $adjustmentOperation->getAsset()->getTicker();

                if (!isset($resourceQuantities[$ticker])) {
                    $resourceQuantities[$ticker] = 0;
                }

                if (!isset($resourceValues[$ticker])) {
                    $resourceValues[$ticker] = 0;
                }

                if ($ticker === $mainTicker) {
                    $resourceQuantities[$ticker] -= $adjustmentOperation->getQuantity();
                    $resourceValues[$ticker] -= $adjustmentOperation->getQuantity();
                } else {
                    $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
                        $owner,
                        $mainTicker,
                        $ticker,
                        $datetime,
                    );
                    Assert::notNull($exchangeRate);

                    $resourceQuantities[$ticker] -= $adjustmentOperation->getQuantity();
                    $resourceValues[$ticker] -= $adjustmentOperation->getQuantity() * $exchangeRate->getRate();
                }
            }
        }

        $totalValue = array_sum($resourceValues);

        ksort($resourceQuantities);
        ksort($resourceValues);

        return [
            'resources' => array_keys($resourceQuantities),
            'quantities' => $resourceQuantities,
            'values' => array_map(
                fn (float $value) => (int) round($value),
                $resourceValues,
            ),
            'share' => array_map(
                fn (float $value) => round($value / $totalValue * 100, self::SHARE_PRECISION),
                $resourceValues,
            ),
        ];
    }
}
