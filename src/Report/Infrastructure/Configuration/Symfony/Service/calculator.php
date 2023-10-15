<?php

declare(strict_types=1);

use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Panda\Report\Domain\Calculator\CalculateAllocationReportData;
use Panda\Report\Domain\Calculator\CalculateAllocationReportDataInterface;
use Panda\Report\Domain\Calculator\CalculatePerformanceReportData;
use Panda\Report\Domain\Calculator\CalculatePerformanceReportDataInterface;
use Panda\Report\Domain\Calculator\OperationValueCalculator;
use Panda\Report\Domain\Calculator\OperationValueCalculatorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CalculateAllocationReportDataInterface::class)
        ->class(CalculateAllocationReportData::class)
        ->args([service(OperationValueCalculatorInterface::class)]);

    $services->set(CalculatePerformanceReportDataInterface::class)
        ->class(CalculatePerformanceReportData::class)
        ->args([service(OperationValueCalculatorInterface::class)]);

    $services->set(OperationValueCalculatorInterface::class)
        ->class(OperationValueCalculator::class)
        ->args([service(ExchangeRateLogRepositoryInterface::class)]);
};
