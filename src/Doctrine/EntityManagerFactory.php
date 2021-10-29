<?php

namespace Powernic\Bot\Doctrine;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    public static function create(string $url): EntityManager
    {
        $config = self::getConfiguration();
        $connection = ['url' => $url];

        return EntityManager::create($connection, $config);
    }

    protected static function getConfiguration(): Configuration
    {
        $isDevMode = true;
        $dir = __DIR__ . "/../Entity";

        return Setup::createAnnotationMetadataConfiguration([$dir], $isDevMode);
    }
}