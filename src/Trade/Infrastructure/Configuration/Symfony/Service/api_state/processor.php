<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Trade\Domain\Factory\OperationFactoryInterface;
use Panda\Trade\Infrastructure\ApiState\Processor\AssetChangeTickerProcessor;
use Panda\Trade\Infrastructure\ApiState\Processor\AssetProcessor;
use Panda\Trade\Infrastructure\ApiState\Processor\TransactionCreateProcessor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AssetProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(AssetChangeTickerProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(TransactionCreateProcessor::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
            service(OperationFactoryInterface::class),
        ])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
