<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Asset\Infrastructure\Doctrine\Orm\AssetRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AssetRepositoryInterface::class)
        ->class(AssetRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
