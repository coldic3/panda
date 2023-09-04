<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Exception;

final class ReportEntryConfigurationMissingKeyException extends \InvalidArgumentException
{
    public function __construct(string $expectedConfigurationKey)
    {
        parent::__construct(
            sprintf('Invalid report configuration, "%s" key is missing.', $expectedConfigurationKey)
        );
    }
}
