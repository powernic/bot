<?php

use Powernic\Bot\DependencyInjection\BotExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

require_once __DIR__ . '/vendor/autoload.php';

$file = __DIR__ . '/cache/container.php';

$containerConfigCache = new ConfigCache($file, true);

if (!$containerConfigCache->isFresh()) {
    $containerBuilder = new ContainerBuilder();
    $extension = new BotExtension();
    $containerBuilder->registerExtension($extension);
    $containerBuilder->loadFromExtension($extension->getAlias());
    $containerBuilder->compile(true);

    $dumper = new PhpDumper($containerBuilder);
    $containerConfigCache->write(
        $dumper->dump(['class' => 'CachedContainer']),
        $containerBuilder->getResources()
    );
}
require $file;
return new CachedContainer();
