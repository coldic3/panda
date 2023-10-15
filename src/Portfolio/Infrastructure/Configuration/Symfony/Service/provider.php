<?php

declare(strict_types=1);

use Panda\Portfolio\Application\Provider\ReportFilePathProvider;
use Panda\Portfolio\Application\Provider\ReportFilePathProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();

    $parameters->set('panda.portfolio.report_file_storage.local_storage', '%kernel.project_dir%/private/reports');

    $services->set(ReportFilePathProviderInterface::class)
        ->class(ReportFilePathProvider::class)
        ->args(['%panda.portfolio.report_file_storage.local_storage%']);
};
