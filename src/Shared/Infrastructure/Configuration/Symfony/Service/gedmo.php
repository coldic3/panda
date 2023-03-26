<?php

declare(strict_types=1);

use Gedmo\Timestampable\TimestampableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set('gedmo.listener.timestampable')
        ->class(TimestampableListener::class)
        ->tag('doctrine.event_subscriber', ['connection' => 'default'])
        ->call('setAnnotationReader', [service('annotation_reader')]);
};
