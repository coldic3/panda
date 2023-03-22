<?php

declare(strict_types=1);

use Panda\Asset\Domain\Factory\AssetFactory;
use Panda\Asset\Domain\Factory\AssetFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AssetFactoryInterface::class)
        ->class(AssetFactory::class);
};
