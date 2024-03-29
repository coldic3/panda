<?php

declare(strict_types=1);

namespace Panda\Report\Domain\ValueObject;

interface ReportEntryInterface
{
    public function getType(): string;

    /** @return array<string, mixed> */
    public function getConfiguration(): array;
}
