<?php

declare(strict_types=1);

use App\Account\Application\Query\User\FindUserQueryHandler;
use App\Account\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindUserQueryHandler::class)
        ->args([service(UserRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'query.bus',
        ]);
};
