<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLiveQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLivesQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogByBaseQuoteTickersAndDatetimeQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogsQueryHandler;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindExchangeRateLogByBaseQuoteTickersAndDatetimeQueryHandler::class)
        ->args([
            service(AuthorizedUserProviderInterface::class),
            service(ExchangeRateLogRepositoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateLiveQueryHandler::class)
        ->args([service(ExchangeRateLiveRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateLivesQueryHandler::class)
        ->args([
            service(AuthorizedUserProviderInterface::class),
            service(ExchangeRateLiveRepositoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateLogQueryHandler::class)
        ->args([service(ExchangeRateLogRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateLogsQueryHandler::class)
        ->args([
            service(AuthorizedUserProviderInterface::class),
            service(ExchangeRateLogRepositoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
