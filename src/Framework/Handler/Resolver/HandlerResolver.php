<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use Powernic\Bot\Framework\Handler\HandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TelegramBot\Api\Client;

abstract class HandlerResolver
{
    protected ContainerInterface $container;
    protected Client $client;
    protected HandlerInterface $handler;

    public function __construct(ContainerInterface $container, Client $client)
    {
        $this->container = $container;
        $this->client = $client;
    }

    abstract public function resolve(): void;

    public function hasHandler(): bool
    {
        return isset($this->handler);
    }

    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param HandlerInterface $handler
     */
    public function setHandler(HandlerInterface $handler): void
    {
        $this->handler = $handler;
    }
}
