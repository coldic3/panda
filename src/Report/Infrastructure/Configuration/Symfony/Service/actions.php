<?php

declare(strict_types=1);

use Panda\Report\Application\Action\DownloadReportAction;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
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
