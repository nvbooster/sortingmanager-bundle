<?php
namespace nvbooster\SortingManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

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
        if (!$container->hasDefinition('nvbooster_sortingmanager')) {
            return;
        }

        $definition = $container->getDefinition('nvbooster_sortingmanager');

        $taggedServices = $container->findTaggedServiceIds('sorting_storage');

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