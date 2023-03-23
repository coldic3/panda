<?php

declare(strict_types=1);

use Panda\Reception\Infrastructure\ApiState\Processor\GreetingCrudProcessor;
use Panda\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GreetingCrudProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
