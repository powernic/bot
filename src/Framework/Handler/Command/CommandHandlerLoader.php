<?php

namespace Powernic\Bot\Framework\Handler\Command;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

final class CommandHandlerLoader implements ContainerInterface
{
    private ContainerInterface $container;
    private array $commandMap;

    /**
     * @param array $commandMap An array with command names as keys and service ids as values
     */
    public function __construct(ContainerInterface $container, array $commandMap)
    {
        $this->container = $container;
        $this->commandMap = $commandMap;
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

        return $this->container->get($this->commandMap[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->commandMap[$id]) && $this->container->has($this->commandMap[$id]);
    }


    /**
     * @return string[] All registered command names
     */
    public function getNames(): array
    {
        return array_keys($this->commandMap);
    }
}
