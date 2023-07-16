<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Tests\Behat\Context\Setup\AssetContext;
use Panda\Tests\Behat\Context\Setup\ExchangeRateContext;
use Panda\Tests\Behat\Context\Setup\PortfolioContext;
use Panda\Tests\Behat\Context\Setup\TransactionContext;
use Panda\Tests\Behat\Context\Setup\UserContext;
use Panda\Trade\Domain\Factory\OperationFactoryInterface;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(UserContext::class)
        ->args([
            service(UserFactoryInterface::class),
            service(UserRepositoryInterface::class),
            service(EntityManagerInterface::class),
            service('lexik_jwt_authentication.encoder.lcobucci'),
            service('security.token_storage'),
        ]);

    $services->set(AssetContext::class)
        ->args([
            service(AssetRepositoryInterface::class),
            service(EntityManagerInterface::class),
            service(CommandBusInterface::class),
        ]);

    $services->set(ExchangeRateContext::class)
        ->args([service(CommandBusInterface::class)]);

    $services->set(TransactionContext::class)
        ->args([
            service(TransactionFactoryInterface::class),
            service(OperationFactoryInterface::class),
            service(TransactionRepositoryInterface::class),
            service(EntityManagerInterface::class),
        ]);

    $services->set(PortfolioContext::class)
        ->args([
            service(AssetContext::class),
            service(PortfolioFactoryInterface::class),
            service(PortfolioItemFactoryInterface::class),
            service(PortfolioRepositoryInterface::class),
            service(CommandBusInterface::class),
            service(EntityManagerInterface::class),
        ]);
};
