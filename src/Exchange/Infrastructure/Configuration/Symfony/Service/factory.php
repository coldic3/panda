<?php

declare(strict_types=1);

use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactory;
use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExchangeRateLiveFactoryInterface::class)
        ->class(ExchangeRateLiveFactory::class);
};
