<?php

declare(strict_types=1);

use ApiPlatform\Api\IriConverterInterface;
use App\Account\Infrastructure\Listener\AuthenticationSuccessEventListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AuthenticationSuccessEventListener::class)
        ->args([service(IriConverterInterface::class)])
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_authentication_success',
        ]);
};
