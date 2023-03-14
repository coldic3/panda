<?php

declare(strict_types=1);

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Messenger\MessengerCommandBus;
use App\Shared\Infrastructure\Messenger\MessengerQueryBus;
use App\Tests\Behat\Context\DemoContext;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(DemoContext::class)
        ->args([service('kernel')]);
};
