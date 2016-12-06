<?php
namespace nvbooster\SortingManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

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
        if (!$container->hasDefinition('nvbooster_sortingmanager')) {
            return;
        }
        $taggedServices = $container->findTaggedServiceIds('sorting_config');

        foreach ($taggedServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            foreach ($tags as $attributes) {
                $definition->addMethodCall('register', array(
                    isset($attributes['alias']) ? $attributes['alias'] : null
                ));
            }
        }
    }
}