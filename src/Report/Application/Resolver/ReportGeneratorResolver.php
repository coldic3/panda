<?php

declare(strict_types=1);

namespace Panda\Report\Application\Resolver;

use Panda\Report\Application\Exception\NoMatchingReportGeneratorFoundException;
use Panda\Report\Application\ReportGenerator\ReportGeneratorInterface;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Webmozart\Assert\Assert;

final readonly class ReportGeneratorResolver implements ReportGeneratorResolverInterface
{
    /** @param iterable<ReportGeneratorInterface> $reportGenerators */
    public function __construct(private iterable $reportGenerators)
    {
        Assert::allIsInstanceOf($reportGenerators, ReportGeneratorInterface::class);
    }

    public function resolve(ReportInterface $report): ReportGeneratorInterface
    {
        foreach ($this->reportGenerators as $generator) {
            if ($generator->supports($report)) {
                return $generator;
            }
        }

        throw new NoMatchingReportGeneratorFoundException($report->getId()->toRfc4122());
    }
}
