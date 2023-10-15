<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Dto;

use Panda\Portfolio\Domain\Exception\ReportEntryConfigurationInvalidKeyTypeException;
use Panda\Portfolio\Domain\Exception\ReportEntryConfigurationMissingKeyException;

readonly class AllocationReportConfigurationDto
{
    private function __construct(public \DateTimeImmutable $datetime)
    {
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        if (!isset($configuration['datetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('datetime');
        }

        if (!is_string($configuration['datetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('datetime', 'string');
        }

        return new self(new \DateTimeImmutable($configuration['datetime']));
    }
}
