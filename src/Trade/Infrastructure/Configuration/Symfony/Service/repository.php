<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Panda\Trade\Infrastructure\Doctrine\Orm\TransactionRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TransactionRepositoryInterface::class)
        ->class(TransactionRepository::class)
        ->args([service(EntityManagerInterface::class)]);
};
