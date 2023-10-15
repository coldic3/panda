<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Report\Application\Query\Report\FindReportQueryHandler;
use Panda\Report\Application\Query\Report\FindReportsQueryHandler;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindReportQueryHandler::class)
        ->args([
            service(ReportRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindReportsQueryHandler::class)
        ->args([
            service(ReportRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
