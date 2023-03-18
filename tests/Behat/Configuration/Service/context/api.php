<?php

declare(strict_types=1);

use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Tests\Behat\Context\Api\AuthContext;
use App\Tests\Behat\Context\Api\UserContext;
use App\Tests\Util\HttpRequestBuilder;
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
