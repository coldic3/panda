<?php

declare(strict_types=1);

use Panda\Portfolio\Application\OpenApi\Factory\PortfolioIdHeaderOpenApiFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PortfolioIdHeaderOpenApiFactory::class)
        ->decorate('api_platform.openapi.factory')
        ->args([service('.inner')]);
};
