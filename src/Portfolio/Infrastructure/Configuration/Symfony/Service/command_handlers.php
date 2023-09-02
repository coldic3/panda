<?php

declare(strict_types=1);

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangeDefaultPortfolioCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioItemCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioCommandHandler;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioItemCommandHandler;
use Panda\Portfolio\Application\Command\Report\CreateReportCommandHandler;
use Panda\Portfolio\Application\Command\Report\GenerateReportCommandHandler;
use Panda\Portfolio\Application\Resolver\ReportGeneratorResolverInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Factory\ReportFactoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CreatePortfolioCommandHandler::class)
        ->args([
            service(PortfolioRepositoryInterface::class),
            service(PortfolioFactoryInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(UpdatePortfolioCommandHandler::class)
        ->args([
            service(PortfolioRepositoryInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(ChangeDefaultPortfolioCommandHandler::class)
        ->args([
            service(PortfolioRepositoryInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(ChangePortfolioItemLongQuantityCommandHandler::class)
        ->args([
            service(PortfolioItemRepositoryInterface::class),
            service(PortfolioResolverInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(CreatePortfolioItemCommandHandler::class)
        ->args([
            service(PortfolioItemRepositoryInterface::class),
            service(PortfolioItemFactoryInterface::class),
            service(PortfolioResolverInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(UpdatePortfolioItemCommandHandler::class)
        ->args([
            service(PortfolioItemRepositoryInterface::class),
            service(PortfolioResolverInterface::class),
            service(ValidatorInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(CreateReportCommandHandler::class)
        ->args([
            service(ReportRepositoryInterface::class),
            service(ReportFactoryInterface::class),
            service(ValidatorInterface::class),
            service(PortfolioRepositoryInterface::class),
            service(EventBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);

    $services->set(GenerateReportCommandHandler::class)
        ->args([
            service(ReportRepositoryInterface::class),
            service(ReportGeneratorResolverInterface::class),
            service(EventBusInterface::class),
        ])
        ->tag('messenger.message_handler', [
            'bus' => 'command.bus',
        ]);
};
