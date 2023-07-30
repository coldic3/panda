<?php

declare(strict_types=1);

use ApiPlatform\State\Pagination\Pagination;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Exchange\Infrastructure\ApiState\Provider\ExchangeRateLiveProvider;
use Panda\Exchange\Infrastructure\ApiState\Provider\ExchangeRateLogProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExchangeRateLiveProvider::class)
        ->args([
            service(QueryBusInterface::class),
            service(Pagination::class),
        ])
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    $services->set(ExchangeRateLogProvider::class)
        ->args([
            service(QueryBusInterface::class),
            service(Pagination::class),
        ])
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
};
