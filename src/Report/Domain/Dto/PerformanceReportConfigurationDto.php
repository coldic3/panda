<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Dto;

use Panda\Report\Domain\Exception\ReportEntryConfigurationInvalidKeyTypeException;
use Panda\Report\Domain\Exception\ReportEntryConfigurationMissingKeyException;

readonly class PerformanceReportConfigurationDto
{
    private function __construct(public \DateTimeImmutable $fromDatetime, public \DateTimeImmutable $toDatetime)
    {
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        if (!isset($configuration['fromDatetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('fromDatetime');
        }

        if (!isset($configuration['toDatetime'])) {
            throw new ReportEntryConfigurationMissingKeyException('toDatetime');
        }

        if (!is_string($configuration['fromDatetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('fromDatetime', 'string');
        }

        if (!is_string($configuration['toDatetime'])) {
            throw new ReportEntryConfigurationInvalidKeyTypeException('toDatetime', 'string');
        }

        return new self(
            new \DateTimeImmutable($configuration['fromDatetime']),
            new \DateTimeImmutable($configuration['toDatetime']),
        );
    }
}
