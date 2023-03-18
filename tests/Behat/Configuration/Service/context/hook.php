<?php

declare(strict_types=1);

use App\Tests\Behat\Context\Hook\ClipboardContext;
use App\Tests\Behat\Context\Hook\DoctrineOrmContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()->public();

    $services->set(DoctrineOrmContext::class)
        ->args([service(EntityManagerInterface::class)]);

    $services->set(ClipboardContext::class);
};
