<?php

declare(strict_types=1);

namespace Panda\Report\Application\ReportGenerator;

use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\ValueObject\ReportFileInterface;

interface ReportGeneratorInterface
{
    public function generate(ReportInterface $report): ReportFileInterface;

    /** @return string[] */
    public function getColumns(): array;

    public function supports(ReportInterface $report): bool;
}
