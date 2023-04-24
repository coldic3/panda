<?php

declare(strict_types=1);

use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Transaction\Domain\Factory\OperationFactoryInterface;
use Panda\Transaction\Infrastructure\ApiState\Processor\TransactionCreateProcessor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TransactionCreateProcessor::class)
        ->args([
            service(CommandBusInterface::class),
            service(QueryBusInterface::class),
            service(OperationFactoryInterface::class),
        ])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
