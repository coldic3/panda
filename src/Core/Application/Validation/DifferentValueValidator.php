<?php

declare(strict_types=1);

namespace Panda\Core\Application\Validation;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class DifferentValueValidator extends ConstraintValidator
{
    public function __construct(private readonly PropertyAccessorInterface $propertyAccessor)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DifferentValue) {
            throw new UnexpectedTypeException($constraint, DifferentValue::class);
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $firstFieldValue = $this->propertyAccessor->getValue($value, $constraint->firstField);
        $secondFieldValue = $this->propertyAccessor->getValue($value, $constraint->secondField);

        if ($firstFieldValue === $secondFieldValue) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->firstField)
                ->setCode(DifferentValue::NOT_DIFFERENT_VALUE_ERROR)
                ->setParameter('{{ field }}', $this->formatValue($constraint->secondField))
                ->addViolation();
        }
    }
}
