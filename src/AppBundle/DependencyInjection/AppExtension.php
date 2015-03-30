<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // parse the configuration
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        // inject the config in the container
        $container->setParameter('app.search.criteria', $config['criteria']);

        // load the services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('repositories.yml');
        $loader->load('listeners.yml');
    }
}
