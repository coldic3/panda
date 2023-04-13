<?php

declare(strict_types=1);

use Panda\Transaction\Domain\Factory\OperationFactory;
use Panda\Transaction\Domain\Factory\OperationFactoryInterface;
use Panda\Transaction\Domain\Factory\TransactionFactory;
use Panda\Transaction\Domain\Factory\TransactionFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TransactionFactoryInterface::class)
        ->class(TransactionFactory::class)
        ->args([service('security.helper')]);

    $services->set(OperationFactoryInterface::class)
        ->class(OperationFactory::class);
};
