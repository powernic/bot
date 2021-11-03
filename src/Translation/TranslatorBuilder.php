<?php

namespace Powernic\Bot\Translation;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Translation\Translator;

class TranslatorBuilder extends Translator
{
    private ContainerBuilder $container;

    public function __construct(ContainerBuilder $container, string $locale, string $cacheDir = null)
    {
        parent::__construct($locale, null, $cacheDir);
        $this->container = $container;
    }
}