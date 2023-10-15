<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Generator;

use Panda\Portfolio\Domain\ValueObject\ReportFile;

interface ReportFileGeneratorInterface
{
    /**
     * @param string[] $columns
     * @param array<int[]|float[]|string[]> $rows
     */
    public function generate(ReportFile $reportFile, array $columns, array $rows): void;
}
