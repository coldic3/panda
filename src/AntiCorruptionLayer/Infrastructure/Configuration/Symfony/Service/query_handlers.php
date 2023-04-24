<?php

declare(strict_types=1);

use Panda\AntiCorruptionLayer\Application\Query\FindResourceQueryHandler;
use Panda\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindResourceQueryHandler::class)
        ->args([service(QueryBusInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);
};
