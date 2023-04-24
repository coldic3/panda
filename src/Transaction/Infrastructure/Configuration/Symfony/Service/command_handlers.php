<?php

declare(strict_types=1);

use Panda\Transaction\Application\Command\Transaction\CreateTransactionCommandHandler;
use Panda\Transaction\Domain\Factory\TransactionFactoryInterface;
use Panda\Transaction\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateTransactionCommandHandler::class)
        ->args([
            service(TransactionRepositoryInterface::class),
            service(TransactionFactoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);
};
