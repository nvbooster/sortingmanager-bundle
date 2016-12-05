<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
            ->children()
                ->booleanNode('enabled')
                    ->treatNullLike(true)
                    ->defaultFalse()
                ->end()
                ->arrayNode('storages')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('cookie')
                            ->treatNullLike(true)
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('session')
                            ->treatNullLike(true)
                            ->defaultTrue()
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