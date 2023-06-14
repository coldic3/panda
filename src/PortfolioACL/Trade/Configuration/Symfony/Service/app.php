<?php

declare(strict_types=1);

use Panda\PortfolioACL\Trade\CommandsAnnouncer\ChangePortfolioItemQuantityWhenTransactionCreated;
use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
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
};
