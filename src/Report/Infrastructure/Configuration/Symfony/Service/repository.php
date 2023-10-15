<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Panda\Report\Infrastructure\Doctrine\Orm\ReportRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ReportRepositoryInterface::class)
        ->class(ReportRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
