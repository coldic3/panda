<?php

declare(strict_types=1);

use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Api\AuthContext;
use Panda\Tests\Behat\Context\Api\UserContext;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(AuthContext::class)
        ->args([service(HttpRequestBuilder::class)]);

    $services->set(UserContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(UserRepositoryInterface::class),
        ]);
};
