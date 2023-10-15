<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Infrastructure\Doctrine\Orm\PortfolioItemRepository;
use Panda\Portfolio\Infrastructure\Doctrine\Orm\PortfolioRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PortfolioRepositoryInterface::class)
        ->class(PortfolioRepository::class)
        ->args([service(EntityManagerInterface::class)]);

    $services->set(PortfolioItemRepositoryInterface::class)
        ->class(PortfolioItemRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
