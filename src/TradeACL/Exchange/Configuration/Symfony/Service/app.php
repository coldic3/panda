<?php

declare(strict_types=1);

use Panda\Core\Application\Query\QueryBusInterface;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;
use Panda\TradeACL\Exchange\IntegrityChecker\CheckCorrespondingExchangeRateLogsExistWhenTransactionCreated;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CheckCorrespondingExchangeRateLogsExistWhenTransactionCreated::class)
        ->args([
            service(QueryBusInterface::class),
            service(PortfolioResolverInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);
};
