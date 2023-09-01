<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ReportGenerator\ReportGeneratorInterface;

interface ReportGeneratorResolverInterface
{
    public function resolve(ReportInterface $report): ReportGeneratorInterface;
}
