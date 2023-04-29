<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Validator\Command;

use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class TransactionOperationAdjustmentsMatchValidation extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionOperationAdjustmentsMatch) {
            throw new UnexpectedTypeException($constraint, TransactionOperationAdjustmentsMatch::class);
        }

        if (!$value instanceof CreateTransactionCommand) {
            throw new UnexpectedValueException($value, CreateTransactionCommand::class);
        }

        $fromAsset = $value->fromOperation?->getAsset();
        $toAsset = $value->toOperation?->getAsset();

        // If both from and to operations are set, then the adjustment operations must match one of them.
        if (null !== $fromAsset && null !== $toAsset) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (
                    !$adjustmentOperation->getAsset()->compare($fromAsset)
                    && !$adjustmentOperation->getAsset()->compare($toAsset)
                ) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }

            return;
        }

        // If only the from operation is set, then the adjustment operations must match it.
        if (null !== $fromAsset) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (!$adjustmentOperation->getAsset()->compare($fromAsset)) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }
        }

        // If only the to operation is set, then the adjustment operations must match it.
        if (null !== $toAsset) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (!$adjustmentOperation->getAsset()->compare($toAsset)) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }
        }
    }
}
