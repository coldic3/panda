<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\EventListener\CreateOrUpdateReversedExchangeRateLiveWhenExchangeRateLiveChanged;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateOrUpdateReversedExchangeRateLiveWhenExchangeRateLiveChanged::class)
        ->args([
            service(ExchangeRateLiveRepositoryInterface::class),
            service(CommandBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'event.bus',
        ]);
};
