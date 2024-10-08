<?php

declare(strict_types=1);

use Panda\Report\Infrastructure\ApiSerializer\InResourceRepresentationNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(InResourceRepresentationNormalizer::class)
        ->tag('serializer.normalizer', ['priority' => 64]);
};
