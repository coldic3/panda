<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Panda\Tests\Behat\Context\Hook\ClipboardContext;
use Panda\Tests\Behat\Context\Hook\DoctrineOrmContext;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(DoctrineOrmContext::class)
        ->args([service(EntityManagerInterface::class)]);

    $services->set(ClipboardContext::class);
};
