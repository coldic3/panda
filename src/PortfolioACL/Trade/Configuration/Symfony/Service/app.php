<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\PortfolioACL\Trade\CommandsAnnouncer\ChangePortfolioItemQuantityWhenTransactionCreated;
use Panda\PortfolioACL\Trade\CommandsAnnouncer\CreateOrUpdatePortfolioItemWhenAssetCreated;
use Panda\PortfolioACL\Trade\CommandsAnnouncer\UpdatePortfolioItemWhenAssetTickerChanged;
use Panda\PortfolioACL\Trade\CommandsAnnouncer\UpdatePortfolioItemWhenAssetUpdated;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChangePortfolioItemQuantityWhenTransactionCreated::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);

    $services->set(CreateOrUpdatePortfolioItemWhenAssetCreated::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);

    $services->set(UpdatePortfolioItemWhenAssetTickerChanged::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);

    $services->set(UpdatePortfolioItemWhenAssetUpdated::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);
};
