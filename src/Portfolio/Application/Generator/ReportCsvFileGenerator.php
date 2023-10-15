<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Generator;

use Panda\Portfolio\Application\Provider\ReportFilePathProviderInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFile;
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
