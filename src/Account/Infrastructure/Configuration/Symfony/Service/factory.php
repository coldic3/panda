<?php

declare(strict_types=1);

use App\Account\Domain\Factory\UserFactory;
use App\Account\Domain\Factory\UserFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(UserFactoryInterface::class)
        ->class(UserFactory::class)
        ->args([service(UserPasswordHasherInterface::class)]);
};
