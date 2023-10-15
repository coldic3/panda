<?php

declare(strict_types=1);

namespace Panda\Report\Application\Resolver;

use Panda\Report\Application\ReportGenerator\ReportGeneratorInterface;
use Panda\Report\Domain\Model\Report\ReportInterface;

interface ReportGeneratorResolverInterface
{
    public function resolve(ReportInterface $report): ReportGeneratorInterface;
}
