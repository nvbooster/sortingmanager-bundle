<?php

namespace nvbooster\SortingManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use nvbooster\SortingManagerBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use nvbooster\SortingManagerBundle\DependencyInjection\Compiler\StorageCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    }
}
