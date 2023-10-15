<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;

final readonly class CalculateAllocationReportData implements CalculateAllocationReportDataInterface
{
    private const SHARE_PRECISION = 2;

    public function __construct(private OperationValueCalculatorInterface $operationValueCalculator)
    {
    }

    public function calculate(
        iterable $transactions,
        OwnerInterface $owner,
        PortfolioInterface $portfolio,
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
                $resourceQuantities[$ticker] = $this->setupArrayKeyValue($resourceQuantities, $ticker);
                $resourceValues[$ticker] = $this->setupArrayKeyValue($resourceValues, $ticker);

                $resourceQuantities[$ticker] -= $fromOperation->getQuantity();
                $resourceValues[$ticker] -= $this->operationValueCalculator->calculate(
                    $fromOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
            }

            if (null !== $toOperation) {
                $ticker = $toOperation->getAsset()->getTicker();
                $resourceQuantities[$ticker] = $this->setupArrayKeyValue($resourceQuantities, $ticker);
                $resourceValues[$ticker] = $this->setupArrayKeyValue($resourceValues, $ticker);

                $resourceQuantities[$ticker] += $toOperation->getQuantity();
                $resourceValues[$ticker] += $this->operationValueCalculator->calculate(
                    $toOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
            }

            foreach ($adjustmentOperations as $adjustmentOperation) {
                $ticker = $adjustmentOperation->getAsset()->getTicker();
                $resourceQuantities[$ticker] = $this->setupArrayKeyValue($resourceQuantities, $ticker);
                $resourceValues[$ticker] = $this->setupArrayKeyValue($resourceValues, $ticker);

                $resourceQuantities[$ticker] -= $adjustmentOperation->getQuantity();
                $resourceValues[$ticker] -= $this->operationValueCalculator->calculate(
                    $adjustmentOperation,
                    $portfolio,
                    $owner,
                    $datetime,
                );
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

    /** @param array<string, int|float> $array */
    private function setupArrayKeyValue(array $array, string $key): int|float
    {
        if (!isset($array[$key])) {
            $array[$key] = 0;
        }

        return $array[$key];
    }
}
