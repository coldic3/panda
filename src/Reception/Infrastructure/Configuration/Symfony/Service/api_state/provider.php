<?php

declare(strict_types=1);

use ApiPlatform\State\Pagination\Pagination;
use App\Reception\Infrastructure\ApiState\Provider\GreetingCrudProvider;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GreetingCrudProvider::class)
        ->args([
            service(QueryBusInterface::class),
            service(Pagination::class),
        ])
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
};
