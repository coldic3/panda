<?php

declare(strict_types=1);

use Panda\Core\Application\Validation\DifferentValueValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set(DifferentValueValidator::class)
        ->args([service(PropertyAccessorInterface::class)])
        ->tag('validator.constraint_validator');
};
