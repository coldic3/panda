<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ReportGenerator;

use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFileInterface;

interface ReportGeneratorInterface
{
    public function generate(ReportInterface $report): ReportFileInterface;
}
