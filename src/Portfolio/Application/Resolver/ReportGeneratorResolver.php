<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Application\Exception\NoMatchingReportGeneratorFoundException;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ReportGenerator\ReportGeneratorInterface;
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
