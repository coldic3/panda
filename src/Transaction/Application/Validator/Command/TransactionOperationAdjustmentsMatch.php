<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Validator\Command;

use Symfony\Component\Validator\Constraint;

final class TransactionOperationAdjustmentsMatch extends Constraint
{
    public string $message = 'The adjustment operations must match the from and to operations.';

    public function validatedBy(): string
    {
        return 'panda_transaction_operation_adjustments_match';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
