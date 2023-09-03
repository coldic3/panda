<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\ReportGenerator\AllocationReportGenerator;
use Panda\Portfolio\Domain\ReportGenerator\PerformanceReportGenerator;
use Panda\Portfolio\Domain\ReportGenerator\ReportGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webmozart\Assert\Assert;

final readonly class ReportGeneratorResolver implements ReportGeneratorResolverInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function resolve(ReportInterface $report): ReportGeneratorInterface
    {
        // FIXME: Use tagged iterator instead of switch
        switch ($report->getEntry()->getType()) {
            case PerformanceReportGenerator::TYPE:
                /** @var PerformanceReportGenerator $generator */
                $generator = $this->container->get(PerformanceReportGenerator::class);
                Assert::isInstanceOf($generator, ReportGeneratorInterface::class);

                return $generator;
            case AllocationReportGenerator::TYPE:
                /** @var AllocationReportGenerator $generator */
                $generator = $this->container->get(AllocationReportGenerator::class);
                Assert::isInstanceOf($generator, ReportGeneratorInterface::class);

                return $generator;
            default:
                throw new \InvalidArgumentException('Invalid report type');
        }
    }
}
