<?php

namespace nvbooster\SortingManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

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
            $loader->load('services.yml');

            $managerDefinition = $container->getDefinition('nvbooster_sortingmanager');
            $managerDefinition->addArgument($config['defaults']);

            if ($config['storages']['cookie']['enabled']) {
                $loader->load('storages/cookies.yml');
                $managerDefinition->addMethodCall('registerStorage', [new Reference('nvbooster_sortingmanager_storage.cookie')]);

                $cookieSaveListener = $container->getDefinition('nvbooster_sortingmanager.cookie_save_listener');
                $cookieSaveListener->replaceArgument(1, $config['storages']['cookie']['expire']);
            }

            if ($config['storages']['session']['enabled']) {
                $loader->load('storages/session.yml');
                $managerDefinition->addMethodCall('registerStorage', [new Reference('nvbooster_sortingmanager_storage.session')]);
            }
        }
    }
}
