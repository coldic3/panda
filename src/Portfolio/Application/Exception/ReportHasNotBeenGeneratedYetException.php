<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Exception;

final class ReportHasNotBeenGeneratedYetException extends \Exception
{
    public function __construct(string $reportId = null)
    {
        parent::__construct(sprintf('Report with ID "%s" has not been generated yet.', $reportId));
    }
}
