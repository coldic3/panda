<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Application\ReportGenerator\ReportGeneratorInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;

interface ReportGeneratorResolverInterface
{
    public function resolve(ReportInterface $report): ReportGeneratorInterface;
}
