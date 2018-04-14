<?php

namespace BVN\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('root');
        $rootNode
            ->children()
            ->arrayNode('storage')
            ->isRequired()
                ->children()
                        ->scalarNode('driver')
                        ->isRequired()
                    ->end()
                        ->scalarNode('path')
                        ->isRequired()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}