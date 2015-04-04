<?php

namespace CrawlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CrawlersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('crawler.registry')) {
            return;
        }

        $registryDef = $container->getDefinition('crawler.registry');
        foreach ($container->findTaggedServiceIds('crawler') as $id => $attributes) {
            $registryDef->addMethodCall('register', array(new Reference($id)));
        }
    }
}
