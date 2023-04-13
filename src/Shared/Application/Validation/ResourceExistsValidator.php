<?php

declare(strict_types=1);

namespace Panda\Shared\Application\Validation;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ResourceExistsValidator extends ConstraintValidator
{
    public function __construct(private IriConverterInterface $iriConverter)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ResourceExists) {
            throw new UnexpectedTypeException($constraint, ResourceExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $iri = '' !== $constraint->uriTemplate ? sprintf($constraint->uriTemplate, (string) $value) : (string) $value;

        try {
            $this->iriConverter->getResourceFromIri($iri);
        } catch (\Throwable) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($iri))
                ->addViolation();
        }
    }
}
