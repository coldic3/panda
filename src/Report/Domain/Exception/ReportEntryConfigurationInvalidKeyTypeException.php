<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Exception;

final class ReportEntryConfigurationInvalidKeyTypeException extends \InvalidArgumentException
{
    public function __construct(string $key, string $expectedType)
    {
        parent::__construct(
            sprintf('Invalid report configuration, "%s" key must be of type "%s".', $key, $expectedType)
        );
    }
}
