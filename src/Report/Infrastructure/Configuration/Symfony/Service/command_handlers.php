<?php

declare(strict_types=1);

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Report\Application\Command\Report\CreateReportCommandHandler;
use Panda\Report\Application\Command\Report\GenerateReportCommandHandler;
use Panda\Report\Application\Resolver\ReportGeneratorResolverInterface;
use Panda\Report\Domain\Factory\ReportFactoryInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

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
