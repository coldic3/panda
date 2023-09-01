<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Exception;

final class InvalidReportEntryTypeException extends \InvalidArgumentException
{
    public function __construct(string $actualType, string $expectedType)
    {
        parent::__construct(
            sprintf('Invalid report entry type, "%s" given, "%s" expected.', $actualType, $expectedType)
        );
    }
}
