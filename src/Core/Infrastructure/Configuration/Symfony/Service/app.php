<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Core\Infrastructure\Doctrine\Listener\PostgresGenerateSchemaListener;
use Panda\Core\Infrastructure\Messenger\MessengerCommandBus;
use Panda\Core\Infrastructure\Messenger\MessengerEventBus;
use Panda\Core\Infrastructure\Messenger\MessengerQueryBus;
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
