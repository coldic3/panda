<?php

declare(strict_types=1);

namespace Panda\Core\Application\Validation;

use Symfony\Component\Validator\Constraint;

final class DifferentValue extends Constraint
{
    public const NOT_DIFFERENT_VALUE_ERROR = '32b5f1aa-ef6c-4820-a68d-64ec807589a3';

    public string $message = 'This field should have a different value than {{ field }} field.';

    public function __construct(
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
        public string $firstField = '',
        public string $secondField = '',
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
