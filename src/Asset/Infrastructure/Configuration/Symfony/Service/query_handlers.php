<?php

declare(strict_types=1);

use Panda\Asset\Application\Query\Asset\FindAssetQueryHandler;
use Panda\Asset\Application\Query\Asset\FindAssetsQueryHandler;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindAssetQueryHandler::class)
        ->args([service(AssetRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);

    $services->set(FindAssetsQueryHandler::class)
        ->args([service(AssetRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);
};
