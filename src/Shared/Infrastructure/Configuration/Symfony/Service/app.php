<?php

declare(strict_types=1);

use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Event\EventBusInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Infrastructure\Doctrine\Listener\PostgresGenerateSchemaListener;
use Panda\Shared\Infrastructure\Messenger\MessengerCommandBus;
use Panda\Shared\Infrastructure\Messenger\MessengerEventBus;
use Panda\Shared\Infrastructure\Messenger\MessengerQueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CommandBusInterface::class)
        ->class(MessengerCommandBus::class)
        ->autowire();

    $services->set(EventBusInterface::class)
        ->class(MessengerEventBus::class)
        ->autowire();

    $services->set(QueryBusInterface::class)
        ->class(MessengerQueryBus::class)
        ->autowire();

    $services
        ->set(PostgresGenerateSchemaListener::class)
        ->tag('doctrine.event_listener', ['event' => 'postGenerateSchema']);
};
