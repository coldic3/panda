<?php

declare(strict_types=1);

use App\Account\Infrastructure\ApiState\Provider\UserProvider;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(UserProvider::class)
        ->args([service(QueryBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
};
