<?php

declare(strict_types=1);

use App\Account\Infrastructure\ApiState\Processor\UserCreateProcesor;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(UserCreateProcesor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
