<?php

declare(strict_types=1);

use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Infrastructure\Doctrine\Orm\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(UserRepositoryInterface::class)
        ->class(UserRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
