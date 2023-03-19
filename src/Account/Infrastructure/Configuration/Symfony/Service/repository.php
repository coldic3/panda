<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Account\Infrastructure\Doctrine\Orm\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(UserRepositoryInterface::class)
        ->class(UserRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
