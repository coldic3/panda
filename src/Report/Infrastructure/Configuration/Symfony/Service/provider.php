<?php

declare(strict_types=1);

use Panda\Report\Application\Provider\ReportFilePathProvider;
use Panda\Report\Application\Provider\ReportFilePathProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();

    $parameters->set('panda.report.report_file_storage.local_storage', '%kernel.project_dir%/private/reports');

    $services->set(ReportFilePathProviderInterface::class)
        ->class(ReportFilePathProvider::class)
        ->args(['%panda.report.report_file_storage.local_storage%']);
};
