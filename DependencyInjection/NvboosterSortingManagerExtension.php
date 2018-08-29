<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManagerBundle\EventListener\SaveSortingCookieListener;
use nvbooster\SortingManagerBundle\Twig\SortableColumnExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NvboosterSortingManagerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['enabled']) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yaml');

            if (!class_exists(\Twig_Extension::class)) {
                $container->removeDefinition(SortableColumnExtension::class);
            }

            $managerDefinition = $container->getDefinition(SortingManager::class);
            $managerDefinition->addArgument($config['defaults']);

            if ($config['storages']['cookie']['enabled']) {
                $loader->load('storages/cookies.yaml');

                $container->getDefinition(SaveSortingCookieListener::class)
                    ->replaceArgument(1, $config['storages']['cookie']['expire'])
                ;
            }

            if ($config['storages']['session']['enabled']) {
                $loader->load('storages/session.yaml');
            }
        }
    }
}
