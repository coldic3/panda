<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Validator\Command;

use Panda\Transaction\Application\Command\Transaction\CreateTransactionCommand;
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

        $fromResource = $value->fromOperation?->getResource();
        $toResource = $value->toOperation?->getResource();

        // If both from and to operations are set, then the adjustment operations must match one of them.
        if (null !== $fromResource && null !== $toResource) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (
                    !$adjustmentOperation->getResource()->compare($fromResource)
                    && !$adjustmentOperation->getResource()->compare($toResource)
                ) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }

            return;
        }

        // If only the from operation is set, then the adjustment operations must match it.
        if (null !== $fromResource) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (!$adjustmentOperation->getResource()->compare($fromResource)) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }
        }

        // If only the to operation is set, then the adjustment operations must match it.
        if (null !== $toResource) {
            foreach ($value->adjustmentOperations as $adjustmentOperation) {
                if (!$adjustmentOperation->getResource()->compare($toResource)) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
            }
        }
    }
}
