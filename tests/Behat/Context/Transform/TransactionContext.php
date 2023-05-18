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
        return (int) ($quantity * self::PRECISION);
    }
}
