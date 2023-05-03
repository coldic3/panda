<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Trade\Domain\Factory\AssetFactory;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Factory\OperationFactory;
use Panda\Trade\Domain\Factory\OperationFactoryInterface;
use Panda\Trade\Domain\Factory\TransactionFactory;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AssetFactoryInterface::class)
        ->class(AssetFactory::class)
        ->args([service(AuthorizedUserProviderInterface::class)]);

    $services->set(TransactionFactoryInterface::class)
        ->class(TransactionFactory::class)
        ->args([service(AuthorizedUserProviderInterface::class)]);

    $services->set(OperationFactoryInterface::class)
        ->class(OperationFactory::class);
};
