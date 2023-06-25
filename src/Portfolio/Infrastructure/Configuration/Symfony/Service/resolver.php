<?php

declare(strict_types=1);

use Panda\Portfolio\Application\Resolver\PortfolioHttpResolver;
use Panda\Portfolio\Application\Resolver\PortfolioResolverInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PortfolioResolverInterface::class)
        ->class(PortfolioHttpResolver::class)
        ->args([
            service('request_stack'),
            service(PortfolioRepositoryInterface::class),
        ]);
};
