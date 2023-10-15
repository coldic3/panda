<?php

declare(strict_types=1);

namespace Panda\Report\Domain\ValueObject;

readonly class ReportEntry implements ReportEntryInterface
{
    /** @param array<string, mixed> $configuration */
    public function __construct(private string $type, private array $configuration)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
