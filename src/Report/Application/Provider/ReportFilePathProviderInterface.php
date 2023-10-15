<?php

declare(strict_types=1);

namespace Panda\Report\Application\Provider;

use Panda\Report\Domain\ValueObject\ReportFile;

interface ReportFilePathProviderInterface
{
    public function provide(ReportFile $reportFile): string;
}
