<?php

declare(strict_types=1);

use Panda\Exchange\Domain\Factory\ExchangeRateFactory;
use Panda\Exchange\Domain\Factory\ExchangeRateFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExchangeRateFactoryInterface::class)
        ->class(ExchangeRateFactory::class);
};
