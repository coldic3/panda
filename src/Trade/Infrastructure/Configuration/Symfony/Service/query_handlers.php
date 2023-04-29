<?php

declare(strict_types=1);

use Panda\Trade\Application\Query\Asset\FindAssetQueryHandler;
use Panda\Trade\Application\Query\Asset\FindAssetsQueryHandler;
use Panda\Trade\Application\Query\Transaction\FindTransactionQueryHandler;
use Panda\Trade\Application\Query\Transaction\FindTransactionsQueryHandler;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindAssetQueryHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service('security.helper'),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);

    $services->set(FindAssetsQueryHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service('security.helper'),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);

    $services->set(FindTransactionQueryHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service('security.helper'),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);

    $services->set(FindTransactionsQueryHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service('security.helper'),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);
};
