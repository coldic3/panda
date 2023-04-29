<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Validator\Command;

use Symfony\Component\Validator\Constraint;

final class TransactionOperationsDiffer extends Constraint
{
    public string $message = 'The from and to operations must not belong to the same asset.';

    public function validatedBy(): string
    {
        return 'panda_transaction_operations_differ';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
