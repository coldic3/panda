<?php

declare(strict_types=1);

use Panda\Asset\Infrastructure\ApiState\Processor\AssetProcessor;
use Panda\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AssetProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
