<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Exception;

final class EmptyAdjustmentsException extends \InvalidArgumentException
{
    protected $message = 'Adjustments cannot be empty.';
}
