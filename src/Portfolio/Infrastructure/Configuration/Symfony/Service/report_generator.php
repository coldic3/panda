<?php

declare(strict_types=1);

use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Panda\Portfolio\Domain\ReportGenerator\AllocationReportGenerator;
use Panda\Portfolio\Domain\ReportGenerator\PerformanceReportGenerator;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PerformanceReportGenerator::class)
        ->public()
        ->args([
            '%kernel.project_dir%',
            service(TransactionRepositoryInterface::class),
            service(ExchangeRateLogRepositoryInterface::class),
        ])
        ->tag('panda.portfolio.report_generator');

    $services->set(AllocationReportGenerator::class)
        ->public()
        ->args([
            '%kernel.project_dir%',
            service(TransactionRepositoryInterface::class),
            service(ExchangeRateLogRepositoryInterface::class),
        ])
        ->tag('panda.portfolio.report_generator');
};
