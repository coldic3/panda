<?php

declare(strict_types=1);

use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Infrastructure\Doctrine\Listener\PostgresGenerateSchemaListener;
use Panda\Shared\Infrastructure\Messenger\MessengerCommandBus;
use Panda\Shared\Infrastructure\Messenger\MessengerQueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CommandBusInterface::class)
        ->class(MessengerCommandBus::class)
        ->args([service(MessageBusInterface::class)]);

    $services->set(QueryBusInterface::class)
        ->class(MessengerQueryBus::class)
        ->args([service(MessageBusInterface::class)]);

    $services
        ->set(PostgresGenerateSchemaListener::class)
        ->tag('doctrine.event_listener', ['event' => 'postGenerateSchema']);
};
