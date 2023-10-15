<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Provider;

use Panda\Portfolio\Domain\ValueObject\ReportFile;

interface ReportFilePathProviderInterface
{
    public function provide(ReportFile $reportFile): string;
}
