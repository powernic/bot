<?php

namespace Powernic\Bot\Framework\Handler\Text;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

final class TextHandlerLoader implements ContainerInterface
{
    private ContainerInterface $container;
    private array $actionMap;

    /**
     * @param array $actionMap An array with command names as keys and service ids as values
     */
    public function __construct(ContainerInterface $container, array $actionMap)
    {
        $this->container = $container;
        $this->actionMap = $actionMap;
    }

    /**
     * @param string $id
     * @return AvailableMessageInterface
     */
    public function get(string $id): AvailableMessageInterface
    {
        if (!$this->has($id)) {
            throw new CommandNotFoundException(sprintf('Command Handler "%s" does not exist.', $id));
        }

        return $this->container->get($this->actionMap[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->actionMap[$id]) && $this->container->has($this->actionMap[$id]);
    }


    /**
     * @return string[] All registered command names
     */
    public function getNames(): array
    {
        return array_keys($this->actionMap);
    }
}
