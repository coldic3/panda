<?php

declare(strict_types=1);

use Panda\Report\Domain\Factory\ReportFactory;
use Panda\Report\Domain\Factory\ReportFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ReportFactoryInterface::class)
        ->class(ReportFactory::class);
};
