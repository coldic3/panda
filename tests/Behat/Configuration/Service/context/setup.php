<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Setup\AssetContext;
use Panda\Tests\Behat\Context\Setup\UserContext;
use Panda\Tests\Behat\Context\Setup\WalletContext;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(UserContext::class)
        ->args([
            service(UserFactoryInterface::class),
            service(UserRepositoryInterface::class),
            service(EntityManagerInterface::class),
            service('lexik_jwt_authentication.encoder.lcobucci'),
            service('security.token_storage'),
        ]);

    $services->set(AssetContext::class)
        ->args([
            service(AssetFactoryInterface::class),
            service(AssetRepositoryInterface::class),
            service(EntityManagerInterface::class),
        ]);

    $services->set(WalletContext::class)
        ->args([
            service(AssetContext::class),
        ]);
};
