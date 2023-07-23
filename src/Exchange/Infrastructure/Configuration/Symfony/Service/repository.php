<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;
use Panda\Exchange\Infrastructure\Doctrine\Orm\ExchangeRateRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExchangeRateRepositoryInterface::class)
        ->class(ExchangeRateRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
