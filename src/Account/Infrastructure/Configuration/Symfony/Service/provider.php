<?php

declare(strict_types=1);

use Panda\Account\Infrastructure\Symfony\Security\AuthorizedUserProvider;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AuthorizedUserProviderInterface::class)
        ->class(AuthorizedUserProvider::class)
        ->args([service('security.helper')]);
};
