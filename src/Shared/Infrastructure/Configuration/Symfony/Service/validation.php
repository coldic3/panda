<?php

declare(strict_types=1);

use ApiPlatform\Api\IriConverterInterface;
use Panda\Shared\Application\Validation\ResourceExistsValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set(ResourceExistsValidator::class)
        ->args([service(IriConverterInterface::class)])
        ->tag('validator.constraint_validator');
};
