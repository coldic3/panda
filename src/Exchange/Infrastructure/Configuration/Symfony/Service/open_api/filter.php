<?php

declare(strict_types=1);

use Panda\Exchange\Infrastructure\OpenApi\Filter\BaseQuoteResourcesFilter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(BaseQuoteResourcesFilter::class)->tag('api_platform.filter');
};
