<?php

declare(strict_types=1);

use Panda\Account\Application\Query\User\FindUserQueryHandler;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FindUserQueryHandler::class)
        ->args([
            service(UserRepositoryInterface::class),
            service(AuthorizedUserProviderInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'query.bus',
        ]);
};
