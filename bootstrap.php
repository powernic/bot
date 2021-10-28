<?php

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require_once __DIR__ . '/vendor/autoload.php';

$file = __DIR__ . '/cache/container.php';

$containerConfigCache = new ConfigCache($file, false);

if (!$containerConfigCache->isFresh()) {
    $containerBuilder = new ContainerBuilder();
    $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/config'));
    try {
        $loader->load('services.yaml');
    } catch (Exception $e) {
    }
    $containerBuilder->compile(true);

    $dumper = new PhpDumper($containerBuilder);
    $containerConfigCache->write(
        $dumper->dump(['class' => 'CachedContainer']),
        $containerBuilder->getResources()
    );
}
require $file;

return new CachedContainer();
