<?php

declare(strict_types=1);

use App\Reception\Application\Command\Greeting\CreateGreetingCommandHandler;
use App\Reception\Application\Command\Greeting\DeleteGreetingCommandHandler;
use App\Reception\Application\Command\Greeting\UpdateGreetingCommandHandler;
use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateGreetingCommandHandler::class)
        ->args([service(GreetingRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(UpdateGreetingCommandHandler::class)
        ->args([service(GreetingRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(DeleteGreetingCommandHandler::class)
        ->args([service(GreetingRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);
};
