<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactory;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactory;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Factory\ReportFactory;
use Panda\Portfolio\Domain\Factory\ReportFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PortfolioFactoryInterface::class)
        ->class(PortfolioFactory::class)
        ->args([service(AuthorizedUserProviderInterface::class)]);

    $services->set(PortfolioItemFactoryInterface::class)
        ->class(PortfolioItemFactory::class);

    $services->set(ReportFactoryInterface::class)
        ->class(ReportFactory::class);
};
