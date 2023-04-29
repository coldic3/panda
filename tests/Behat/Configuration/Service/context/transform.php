<?php

declare(strict_types=1);

use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Transform\AssetContext;
use Panda\Tests\Behat\Context\Transform\UserContext;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(AssetContext::class)
        ->args([service(AssetRepositoryInterface::class)]);

    $services->set(UserContext::class)
        ->args([service(UserRepositoryInterface::class)]);
};
