<?php

declare(strict_types=1);

use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Reception\Infrastructure\Doctrine\Orm\GreetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GreetingRepositoryInterface::class)
        ->class(GreetingRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
