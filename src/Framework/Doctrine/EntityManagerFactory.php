<?php

namespace Powernic\Bot\Framework\Doctrine;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Finder\Finder;

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
        $paths = self::getEntityPaths();

        return Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    }

    /**
     * @return array<int, string>
     */
    private static function getEntityPaths(): array
    {
        $finder = new Finder();
        $finder->in(__DIR__ . "/../../")->directories()->name('Entity');
        $paths = [];
        foreach ($finder as $dir) {
            $paths[] = $dir->getPath();
        }

        return $paths;
    }
}