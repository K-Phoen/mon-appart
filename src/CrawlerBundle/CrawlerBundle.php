<?php

namespace CrawlerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use CrawlerBundle\DependencyInjection\Compiler\CrawlersCompilerPass;

class CrawlerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CrawlersCompilerPass());
    }
}
