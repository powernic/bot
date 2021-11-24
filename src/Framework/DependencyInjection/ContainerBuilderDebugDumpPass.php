<?php

namespace Powernic\Bot\Framework\DependencyInjection;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;

class ContainerBuilderDebugDumpPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $file = $container->getParameterBag()->resolveString('%kernel.build_dir%/%kernel.container_class%.xml');
        $cache = new ConfigCache($file, true);
        if (!$cache->isFresh()) {
            $cache->write((new XmlDumper($container))->dump(), $container->getResources());
        }
    }
}
