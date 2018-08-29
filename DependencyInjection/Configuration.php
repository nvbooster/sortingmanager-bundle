<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nvbooster_sorting_manager');

        $rootNode
            ->fixXmlConfig('storage')
            ->canBeEnabled()
            ->children()
                ->arrayNode('storages')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('cookie')
                            ->canBeEnabled()
                            ->children()
                                ->integerNode('expire')
                                    ->defaultValue(604800)
                                    ->min(0)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('session')
                            ->canBeDisabled()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('storage')
                            ->defaultValue('array')
                        ->end()
                        ->integerNode('sort_columns_count')
                            ->min(1)
                            ->defaultValue(1)
                        ->end()
                        ->scalarNode('param_column')
                            ->defaultValue('sc')
                        ->end()
                        ->scalarNode('param_order')
                            ->defaultValue('so')
                        ->end()
                        ->scalarNode('column_ascend_class')
                            ->defaultValue('sm_asc')
                        ->end()
                        ->scalarNode('column_descend_class')
                            ->defaultValue('sm_desc')
                        ->end()
                        ->scalarNode('column_sortable_class')
                            ->defaultValue('sm_column')
                        ->end()
                        ->scalarNode('translation_domain')
                            ->defaultValue('sortingmanager')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
