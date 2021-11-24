<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use TelegramBot\Api\Types\Message;

class CommandHandlerResolver extends HandlerResolver
{
    public function resolve(): void
    {
        if ($this->container->has('handler.command.loader')) {
            $commandHandlerLoader = $this->container->get('handler.command.loader');
            $commands = $commandHandlerLoader->getNames();
            foreach ($commands as $command) {
                $this->client->command(
                    $command,
                    function (Message $message) use ($commandHandlerLoader, $command) {
                        $this->setHandler($commandHandlerLoader->get($command)->setMessage($message));
                    }
                );
            }
        }
    }
}
