<?php

namespace Powernic\Bot\Framework\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\UnknownEntityNamespace;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Proxy\Proxy;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;

/**
 * References all Doctrine connections and entity managers in a given Container.
 */
class Registry extends ManagerRegistry
{
    /**
     * @param string[] $connections
     * @param string[] $entityManagers
     */
    public function __construct(
        ContainerInterface $container,
        array $connections,
        array $entityManagers,
        string $defaultConnection,
        string $defaultEntityManager
    ) {
        $this->container = $container;
        parent::__construct(
            'ORM',
            $connections,
            $entityManagers,
            $defaultConnection,
            $defaultEntityManager,
            Proxy::class
        );
    }

    /**
     * Resolves a registered namespace alias to the full namespace.
     *
     * This method looks for the alias in all registered entity managers.
     *
     * @param string $alias The alias
     *
     * @return string The full namespace
     * @throws UnknownEntityNamespace
     *
     */
    public function getAliasNamespace($alias)
    {
        foreach (array_keys($this->getManagers()) as $name) {
            $objectManager = $this->getManager($name);

            if (!$objectManager instanceof EntityManagerInterface) {
                continue;
            }

            try {
                return $objectManager->getConfiguration()->getEntityNamespace($alias);
            } catch (ORMException $e) {
            }
        }

        throw new UnknownEntityNamespace($alias);
    }

}
