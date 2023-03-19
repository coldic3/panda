<?php

declare(strict_types=1);

use Panda\Reception\Application\Query\Greeting\FindGreetingQueryHandler;
use Panda\Reception\Application\Query\Greeting\FindGreetingsQueryHandler;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindGreetingQueryHandler::class)
        ->args([service(GreetingRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);

    $services->set(FindGreetingsQueryHandler::class)
        ->args([service(GreetingRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);
};
