<?php

namespace Powernic\Bot\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class BotExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $container->getParameterBag()->add($this->getKernelParameters());
    }

    /**
     * @return array
     */
    private function getKernelParameters(): array
    {
        return [
            'kernel.project_dir' => __DIR__ . '/../../',
            'kernel.cache_dir' => __DIR__ . '/../../cache',
        ];
    }
}