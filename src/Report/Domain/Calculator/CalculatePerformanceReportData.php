<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\Dto\PerformanceReportConfigurationDto;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;

final readonly class CalculatePerformanceReportData implements CalculatePerformanceReportDataInterface
{
    private const NOT_APPLICABLE = 'N/A';
    private const RATE_TO_RETURN_PRECISION = 2;

    public function __construct(private OperationValueCalculatorInterface $operationValueCalculator)
    {
    }

    public function calculate(
        iterable $initialValueTransactions,
        iterable $finalValueTransactions,
        OwnerInterface $owner,
        PortfolioInterface $portfolio,
        PerformanceReportConfigurationDto $configuration,
    ): array {
        $initialValueOfResources = $this->calculateValueOfResources(
            $initialValueTransactions,
            $owner,
            $portfolio,
            $configuration->fromDatetime,
        );

        $finalValueOfResources = $initialValueOfResources + $this->calculateValueOfResources(
            $finalValueTransactions,
            $owner,
            $portfolio,
            $configuration->toDatetime,
        );

        $profitLoss = $finalValueOfResources - $initialValueOfResources;
        $rateToReturn = 0 === $initialValueOfResources
            ? self::NOT_APPLICABLE
            : sprintf(
                '%.2f%%',
                round($profitLoss / $initialValueOfResources * 100, self::RATE_TO_RETURN_PRECISION)
            );

        return [
            'initialValueOfResources' => $initialValueOfResources,
            'finalValueOfResources' => $finalValueOfResources,
            'profitLoss' => $profitLoss,
            'rateToReturn' => $rateToReturn,
        ];
    }

    /**
     * @param iterable<TransactionInterface> $transactions
     */
    private function calculateValueOfResources(
        iterable $transactions,
        OwnerInterface $owner,
        PortfolioInterface $portfolio,
        \DateTimeImmutable $datetime
    ): int {
        $valueOfResources = .0;

        foreach ($transactions as $transaction) {
            $fromOperation = $transaction->getFromOperation();
            $toOperation = $transaction->getToOperation();
            $adjustmentOperations = $transaction->getAdjustmentOperations();

            if (null !== $fromOperation) {
                $valueOfResources -= $this->operationValueCalculator->calculate(
                    $fromOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
            }

            if (null !== $toOperation) {
                $valueOfResources += $this->operationValueCalculator->calculate(
                    $toOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
            }

            foreach ($adjustmentOperations as $adjustmentOperation) {
                $valueOfResources -= $this->operationValueCalculator->calculate(
                    $adjustmentOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
            }
        }

        return (int) round($valueOfResources);
    }
}
