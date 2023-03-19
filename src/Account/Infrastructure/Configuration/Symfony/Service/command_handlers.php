<?php

declare(strict_types=1);

use Panda\Account\Application\Command\User\CreateUserCommandHandler;
use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreateUserCommandHandler::class)
        ->args([
            service(UserRepositoryInterface::class),
            service(UserFactoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);
};
