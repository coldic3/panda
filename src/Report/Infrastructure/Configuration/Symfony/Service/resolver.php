<?php

declare(strict_types=1);

use Panda\Report\Application\Resolver\ReportGeneratorResolver;
use Panda\Report\Application\Resolver\ReportGeneratorResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ReportGeneratorResolverInterface::class)
        ->class(ReportGeneratorResolver::class)
        ->args([tagged_iterator('panda.portfolio.report_generator')]);
};
