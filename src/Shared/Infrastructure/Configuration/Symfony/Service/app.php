<?php

declare(strict_types=1);

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Messenger\MessengerCommandBus;
use App\Shared\Infrastructure\Messenger\MessengerQueryBus;
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
};
