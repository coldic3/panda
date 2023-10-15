<?php

declare(strict_types=1);

use Panda\Report\Application\Generator\ReportCsvFileGenerator;
use Panda\Report\Application\Generator\ReportFileGeneratorInterface;
use Panda\Report\Application\Provider\ReportFilePathProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ReportFileGeneratorInterface::class)
        ->class(ReportCsvFileGenerator::class)
        ->args([service(ReportFilePathProviderInterface::class)]);
};
