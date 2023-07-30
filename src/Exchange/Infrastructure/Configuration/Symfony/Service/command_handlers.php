<?php

declare(strict_types=1);

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLive\CreateExchangeRateLiveCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRateLive\DeleteExchangeRateLiveCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRateLive\UpdateExchangeRateLiveCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRateLog\CreateExchangeRateLogCommandHandler;
use Panda\Exchange\Application\Command\ExchangeRateLog\DeleteExchangeRateLogCommandHandler;
use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactoryInterface;
use Panda\Exchange\Domain\Factory\ExchangeRateLogFactoryInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateExchangeRateLiveCommandHandler::class)
        ->args([
            service(ExchangeRateLiveRepositoryInterface::class),
            service(ExchangeRateLiveFactoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(UpdateExchangeRateLiveCommandHandler::class)
        ->args([
            service(ExchangeRateLiveRepositoryInterface::class),
            service(EventBusInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(DeleteExchangeRateLiveCommandHandler::class)
        ->args([service(ExchangeRateLiveRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(CreateExchangeRateLogCommandHandler::class)
        ->args([
            service(ExchangeRateLogRepositoryInterface::class),
            service(ExchangeRateLogFactoryInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(DeleteExchangeRateLogCommandHandler::class)
        ->args([service(ExchangeRateLogRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);
};
