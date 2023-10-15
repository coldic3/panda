<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Report\Application\EventListener\Report\GenerateReportWhenReportCreated;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GenerateReportWhenReportCreated::class)
        ->args([
            service(ReportRepositoryInterface::class),
            service(CommandBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);
};
