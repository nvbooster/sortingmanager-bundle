<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use nvbooster\SortingManager\SortingManager;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class ConfigCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     *
     * @see CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(SortingManager::class)) {
            return;
        }
        $taggedServices = $container->findTaggedServiceIds('nvbooster_sortingmanager.config');

        foreach ($taggedServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            foreach ($tags as $attributes) {
                $definition->addMethodCall('register', [
                    isset($attributes['alias']) ? $attributes['alias'] : null
                ]);
            }
        }
    }
}