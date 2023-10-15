<?php

declare(strict_types=1);

use Panda\Portfolio\Application\Generator\ReportFileGeneratorInterface;
use Panda\Portfolio\Application\ReportGenerator\AllocationReportGenerator;
use Panda\Portfolio\Application\ReportGenerator\PerformanceReportGenerator;
use Panda\Portfolio\Domain\Calculator\CalculateAllocationReportDataInterface;
use Panda\Portfolio\Domain\Calculator\CalculatePerformanceReportDataInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PerformanceReportGenerator::class)
        ->public()
        ->args([
            service(ReportFileGeneratorInterface::class),
            service(CalculatePerformanceReportDataInterface::class),
            service(TransactionRepositoryInterface::class),
        ])
        ->tag('panda.portfolio.report_generator');

    $services->set(AllocationReportGenerator::class)
        ->public()
        ->args([
            service(ReportFileGeneratorInterface::class),
            service(CalculateAllocationReportDataInterface::class),
            service(TransactionRepositoryInterface::class),
        ])
        ->tag('panda.portfolio.report_generator');
};
