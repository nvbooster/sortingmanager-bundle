<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use nvbooster\SortingManager\SortingManager;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class StorageCompilerPass implements CompilerPassInterface
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

        $definition = $container->getDefinition(SortingManager::class);

        $taggedServices = $container->findTaggedServiceIds('nvbooster_sortingmanager.storage');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('registerStorage', [
                    new Reference($id),
                    $attributes['alias'] ?: null
                ]);
            }
        }
    }
}