<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\EventListener\CreateOrUpdateReversedExchangeRateWhenExchangeRateChanged;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateOrUpdateReversedExchangeRateWhenExchangeRateChanged::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(CommandBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);
};
