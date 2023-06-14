<?php

namespace Panda\Core\Infrastructure\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getConfigDir();

        $container->import($configDir.'/{Package}/*.{php,yaml}');
        $container->import($configDir.'/{Package}/'.$this->environment.'/*.{php,yaml}');

        $container->import($configDir.'/services.yaml');
        $container->import($configDir.'/services_'.$this->environment.'.yaml', null, true);

        if ('test' === $this->environment) {
            $container->import($this->getProjectDir().'/tests/Behat/Configuration/services.yaml');
        }
    }

    private function getConfigDir(): string
    {
        return $this->getProjectDir().'/src/Core/Infrastructure/Configuration/Symfony';
    }
}
