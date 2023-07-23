<?php

declare(strict_types=1);

use Panda\Exchange\Application\Query\ExchangeRate\FindExchangeRateQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRate\FindExchangeRatesQueryHandler;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindExchangeRateQueryHandler::class)
        ->args([service(ExchangeRateRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRatesQueryHandler::class)
        ->args([service(ExchangeRateRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
