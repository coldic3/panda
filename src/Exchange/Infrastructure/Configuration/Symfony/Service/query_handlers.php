<?php

declare(strict_types=1);

use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLiveQueryHandler;
use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLivesQueryHandler;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindExchangeRateLiveQueryHandler::class)
        ->args([service(ExchangeRateLiveRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);

    $services->set(FindExchangeRateLivesQueryHandler::class)
        ->args([service(ExchangeRateLiveRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
