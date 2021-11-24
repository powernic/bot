<?php

namespace Powernic\Bot;

use Exception;
use Symfony\Component\HttpKernel\KernelInterface;
use TelegramBot\Api\Client;

final class Application
{
    private KernelInterface $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @throws Exception
     */
    public function boot()
    {
        $this->kernel->boot();
        $containerHandlerResolver = $this->kernel->getContainer()->get('handler_resolver.container');
        $containerHandlerResolver->resolve();
        $client = $this->kernel->getContainer()->get(Client::class);
        $client->run();
        $containerHandlerResolver->getHandler()->handle();
    }

}
