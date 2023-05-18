<?php

declare(strict_types=1);

use Panda\Trade\Infrastructure\OpenApi\Filter\ConcludedAtFilter;
use Panda\Trade\Infrastructure\OpenApi\Filter\OperationAssetFilter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ConcludedAtFilter::class)->tag('api_platform.filter');
    $services->set(OperationAssetFilter::class)->tag('api_platform.filter');
};
