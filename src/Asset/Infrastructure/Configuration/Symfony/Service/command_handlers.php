<?php

declare(strict_types=1);

use Panda\Asset\Application\Command\Asset\CreateAssetCommandHandler;
use Panda\Asset\Application\Command\Asset\DeleteAssetCommandHandler;
use Panda\Asset\Application\Command\Asset\UpdateAssetCommandHandler;
use Panda\Asset\Domain\Factory\AssetFactoryInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateAssetCommandHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(AssetFactoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(UpdateAssetCommandHandler::class)
        ->args([service(AssetRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(DeleteAssetCommandHandler::class)
        ->args([service(AssetRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);
};
