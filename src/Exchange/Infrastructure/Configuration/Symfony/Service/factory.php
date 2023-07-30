<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactory;
use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactoryInterface;
use Panda\Exchange\Domain\Factory\ExchangeRateLogFactory;
use Panda\Exchange\Domain\Factory\ExchangeRateLogFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExchangeRateLiveFactoryInterface::class)
        ->args([service(AuthorizedUserProviderInterface::class)])
        ->class(ExchangeRateLiveFactory::class);

    $services->set(ExchangeRateLogFactoryInterface::class)
        ->args([service(AuthorizedUserProviderInterface::class)])
        ->class(ExchangeRateLogFactory::class);
};
