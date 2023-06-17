<?php

declare(strict_types=1);

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Application\Command\Asset\CreateAssetCommandHandler;
use Panda\Trade\Application\Command\Asset\DeleteAssetCommandHandler;
use Panda\Trade\Application\Command\Asset\UpdateAssetCommandHandler;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommandHandler;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateAssetCommandHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(AssetFactoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(UpdateAssetCommandHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(DeleteAssetCommandHandler::class)
        ->args([service(AssetRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(CreateTransactionCommandHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service(TransactionFactoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);
};
