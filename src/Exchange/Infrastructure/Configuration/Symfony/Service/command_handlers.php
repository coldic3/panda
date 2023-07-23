<?php

declare(strict_types=1);

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Exchange\Application\Command\ExchangeRate\CreateExchangeRateCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRate\DeleteExchangeRateCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRate\UpdateExchangeRateCommandHandler;
use Panda\Exchange\Domain\Factory\ExchangeRateFactoryInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateExchangeRateCommandHandler::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(ExchangeRateFactoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(UpdateExchangeRateCommandHandler::class)
        ->args([
            service(ExchangeRateRepositoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(DeleteExchangeRateCommandHandler::class)
        ->args([service(ExchangeRateRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);
};
