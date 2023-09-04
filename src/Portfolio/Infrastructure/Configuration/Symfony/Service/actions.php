<?php

declare(strict_types=1);

use Panda\Portfolio\Application\Action\DownloadReportAction;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DownloadReportAction::class)
        ->public()
        ->args([
            service(ReportRepositoryInterface::class),
            '%kernel.project_dir%',
        ])
        ->tag('controller.service_arguments');
};
