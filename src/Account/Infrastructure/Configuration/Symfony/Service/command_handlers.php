<?php

declare(strict_types=1);

use App\Account\Application\Command\User\CreateUserCommandHandler;
use App\Account\Domain\Factory\UserFactoryInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
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
