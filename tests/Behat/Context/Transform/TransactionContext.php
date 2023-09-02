<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;

class TransactionContext implements Context
{
    private const PRECISION = 100;

    /**
     * @Transform :quantity
     * @Transform :fromQuantity
     * @Transform :toQuantity
     * @Transform :adjustmentQuantity
     */
    public function quantity(float $quantity): int
    {
        return (int) $quantity;
    }

    /**
     * @Transform :preciseQuantity
     * @Transform :fromPreciseQuantity
     * @Transform :toPreciseQuantity
     * @Transform :adjustmentPreciseQuantity
     */
    public function money(float $preciseQuantity): int
    {
        return (int) ($preciseQuantity * self::PRECISION);
    }

    private function isDecimal(float $number): bool
    {
        return floor($number) !== $number;
    }
}
