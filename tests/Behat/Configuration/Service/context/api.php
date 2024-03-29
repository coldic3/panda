<?php

declare(strict_types=1);

use ApiPlatform\Api\IriConverterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Api\AssetContext;
use Panda\Tests\Behat\Context\Api\AuthContext;
use Panda\Tests\Behat\Context\Api\ExchangeRateContext;
use Panda\Tests\Behat\Context\Api\ExchangeRateLogContext;
use Panda\Tests\Behat\Context\Api\PortfolioContext;
use Panda\Tests\Behat\Context\Api\ReportContext;
use Panda\Tests\Behat\Context\Api\TransactionContext;
use Panda\Tests\Behat\Context\Api\UserContext;
use Panda\Tests\Util\HttpRequestBuilder;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(AuthContext::class)
        ->args([service(HttpRequestBuilder::class)]);

    $services->set(UserContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(UserRepositoryInterface::class),
        ]);

    $services->set(AssetContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(AssetRepositoryInterface::class),
            service(IriConverterInterface::class),
        ]);

    $services->set(ExchangeRateContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(IriConverterInterface::class),
            service(EntityManagerInterface::class),
        ]);

    $services->set(ExchangeRateLogContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(IriConverterInterface::class),
            service(EntityManagerInterface::class),
        ]);

    $services->set(TransactionContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(IriConverterInterface::class),
            service(EntityManagerInterface::class),
        ]);

    $services->set(PortfolioContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(IriConverterInterface::class),
        ]);

    $services->set(ReportContext::class)
        ->args([
            service(HttpRequestBuilder::class),
            service(IriConverterInterface::class),
            '%kernel.project_dir%',
        ]);
};
