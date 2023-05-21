<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Exception;

class NegativeTotalQuantityException extends \InvalidArgumentException
{
    public function __construct(int $quantity)
    {
        parent::__construct(sprintf('Total quantity must be greater than or equal 0, %d given.', $quantity));
    }
}
