<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Validator\Command;

use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class TransactionOperationsDifferValidation extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionOperationsDiffer) {
            throw new UnexpectedTypeException($constraint, TransactionOperationsDiffer::class);
        }

        if (!$value instanceof CreateTransactionCommand) {
            throw new UnexpectedValueException($value, CreateTransactionCommand::class);
        }

        $fromAsset = $value->fromOperation?->getAsset();
        $toAsset = $value->toOperation?->getAsset();

        if (null !== $fromAsset && null !== $toAsset && $fromAsset->compare($toAsset)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
