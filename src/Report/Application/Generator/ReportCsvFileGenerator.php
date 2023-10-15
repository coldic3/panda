<?php

declare(strict_types=1);

namespace Panda\Report\Application\Generator;

use Panda\Report\Application\Provider\ReportFilePathProviderInterface;
use Panda\Report\Domain\ValueObject\ReportFile;
use Webmozart\Assert\Assert;

final readonly class ReportCsvFileGenerator implements ReportFileGeneratorInterface
{
    public function __construct(private ReportFilePathProviderInterface $reportFilePathProvider)
    {
    }

    public function generate(ReportFile $reportFile, array $columns, array $rows): void
    {
        $handle = fopen($this->reportFilePathProvider->provide($reportFile), 'w');
        Assert::notFalse($handle);

        fputcsv($handle, $columns);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }
}
