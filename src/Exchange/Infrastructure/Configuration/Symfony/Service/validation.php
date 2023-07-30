<?php

declare(strict_types=1);

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Exchange\Application\Validation\ExchangeRateLogDatetimeOverlappedValidator;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set(ExchangeRateLogDatetimeOverlappedValidator::class)
        ->args([
            service(AuthorizedUserProviderInterface::class),
            service(ExchangeRateLogRepositoryInterface::class),
        ])
        ->tag('validator.constraint_validator');
};
