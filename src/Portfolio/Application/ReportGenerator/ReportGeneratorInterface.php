<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\ReportGenerator;

use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFileInterface;

interface ReportGeneratorInterface
{
    public function generate(ReportInterface $report): ReportFileInterface;

    /** @return string[] */
    public function getColumns(): array;

    public function supports(ReportInterface $report): bool;
}
