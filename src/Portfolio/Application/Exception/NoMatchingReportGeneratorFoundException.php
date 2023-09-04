<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Exception;

final class NoMatchingReportGeneratorFoundException extends \Exception
{
    public function __construct(string $reportId = null)
    {
        parent::__construct(sprintf('No matching ReportGeneratorInterface implementation found for Report with ID "%s".', $reportId));
    }
}
