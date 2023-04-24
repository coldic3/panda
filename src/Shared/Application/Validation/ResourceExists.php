<?php

declare(strict_types=1);

namespace Panda\Shared\Application\Validation;

use Symfony\Component\Validator\Constraint;

final class ResourceExists extends Constraint
{
    public string $message = 'The {{ value }} resource does not exist or is not a valid IRI.';

    public function __construct(
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
        public string $uriTemplate = '',
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
