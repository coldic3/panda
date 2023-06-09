<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Transform\AssetContext;
use Panda\Tests\Behat\Context\Transform\DateTimeContext;
use Panda\Tests\Behat\Context\Transform\PortfolioContext;
use Panda\Tests\Behat\Context\Transform\TransactionContext;
use Panda\Tests\Behat\Context\Transform\UserContext;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(AssetContext::class)
        ->args([service(EntityManagerInterface::class)]);

    $services->set(PortfolioContext::class)
        ->args([service(EntityManagerInterface::class)]);

    $services->set(DateTimeContext::class);

    $services->set(TransactionContext::class);

    $services->set(UserContext::class)
        ->args([service(UserRepositoryInterface::class)]);
};
