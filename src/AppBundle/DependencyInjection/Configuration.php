<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $this->addCriteriaSection($rootNode);

        return $treeBuilder;
    }

    private function addCriteriaSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                 ->variableNode('criteria')
                    ->treatNullLike(array())
                 ->end()
            ->end()
        ;
    }
}
