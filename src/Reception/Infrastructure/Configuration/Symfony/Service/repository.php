<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Reception\Infrastructure\Doctrine\Orm\GreetingRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GreetingRepositoryInterface::class)
        ->class(GreetingRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
