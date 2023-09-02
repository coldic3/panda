<?php

declare(strict_types=1);

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioChangeDefaultProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioCreateProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioUpdateProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Processor\ReportCreateProcessor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PortfolioCreateProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(PortfolioUpdateProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(PortfolioChangeDefaultProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(ReportCreateProcessor::class)
        ->args([service(CommandBusInterface::class)])
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
};
