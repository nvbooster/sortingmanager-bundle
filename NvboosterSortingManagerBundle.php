<?php

namespace nvbooster\SortingManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use nvbooster\SortingManagerBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use nvbooster\SortingManagerBundle\DependencyInjection\Compiler\StorageCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class NvboosterSortingManagerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigCompilerPass());
        $container->addCompilerPass(new StorageCompilerPass());
        $container->registerForAutoconfiguration(ConfigStorageInterface::class)
            ->addTag('nvbooster_sortingmanager.storage')
        ;
    }
}
