<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Exception;

class NegativeTotalQuantityException extends \InvalidArgumentException
{
    public function __construct(int $quantity, int $totalQuantity)
    {
        parent::__construct(sprintf(
            'The total quantity must be greater than or equal to 0.'
            .' The total quantity is %d, so cannot be reduced by %d as this will result in a negative quantity %d.',
            $totalQuantity,
            $quantity,
            $totalQuantity - $quantity,
        ));
    }
}
