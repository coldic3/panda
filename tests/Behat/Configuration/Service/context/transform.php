<?php

declare(strict_types=1);

use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Tests\Behat\Context\Transform\AssetContext;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(AssetContext::class)
        ->args([service(AssetRepositoryInterface::class)]);
};
