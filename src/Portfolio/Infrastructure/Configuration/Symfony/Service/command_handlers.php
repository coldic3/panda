<?php

declare(strict_types=1);

use Panda\Portfolio\Application\Command\Portfolio\ChangeDefaultPortfolioCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioCommandHandler;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreatePortfolioCommandHandler::class)
        ->args([
            service(PortfolioRepositoryInterface::class),
            service(PortfolioFactoryInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(UpdatePortfolioCommandHandler::class)
        ->args([service(PortfolioRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);

    $services->set(ChangeDefaultPortfolioCommandHandler::class)
        ->args([service(PortfolioRepositoryInterface::class)])
        ->tag('messenger.message_handler', [
            'bus_name' => 'command.bus',
        ]);
};
