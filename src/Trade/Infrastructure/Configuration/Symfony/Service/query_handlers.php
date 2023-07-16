<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Trade\Application\Query\Asset\FindAssetQueryHandler;
use Panda\Trade\Application\Query\Asset\FindAssetsQueryHandler;
use Panda\Trade\Application\Query\ExchangeRate\FindExchangeRateByAssetIdsQueryHandler;
use Panda\Trade\Application\Query\ExchangeRate\FindExchangeRateQueryHandler;
use Panda\Trade\Application\Query\ExchangeRate\FindExchangeRatesQueryHandler;
use Panda\Trade\Application\Query\Transaction\FindTransactionQueryHandler;
use Panda\Trade\Application\Query\Transaction\FindTransactionsQueryHandler;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindAssetQueryHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindAssetsQueryHandler::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateByAssetIdsQueryHandler::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(AssetRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateQueryHandler::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRatesQueryHandler::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindTransactionQueryHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindTransactionsQueryHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
