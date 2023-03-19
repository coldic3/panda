<?php

declare(strict_types=1);

use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Setup\UserContext;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(UserContext::class)
        ->args([
            service(UserFactoryInterface::class),
            service(UserRepositoryInterface::class),
            service('lexik_jwt_authentication.encoder.lcobucci'),
        ]);
};
