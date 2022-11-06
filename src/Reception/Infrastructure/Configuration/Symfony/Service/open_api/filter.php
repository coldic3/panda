<?php

declare(strict_types=1);

use App\Reception\Infrastructure\OpenApi\Filter\NameFilter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(NameFilter::class)
        ->tag('api_platform.filter');
};
