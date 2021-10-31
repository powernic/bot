<?php

namespace Powernic\Bot;

use Closure;
use Powernic\Bot\CommandHandler\CommandHandlerLoader;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\InvalidJsonException;
use TelegramBot\Api\Types\Message;

final class Application
{
    private Client $client;
    private BotApi $bot;
    private CommandHandlerLoader $commandHandlerLoader;

    public function __construct(Client $client, BotApi $bot, CommandHandlerLoader $commandHandlerLoader)
    {
        $this->client = $client;
        $this->bot = $bot;
        $this->commandHandlerLoader = $commandHandlerLoader;
    }

    /**
     * @throws InvalidJsonException
     */
    public function run()
    {
        $this->runCommandHandlers();
        $this->client->run();
    }

    private function runCommandHandlers()
    {
        $commands = $this->commandHandlerLoader->getNames();
        foreach ($commands as $command) {
            $this->client->command($command, function (Message $message) use ($command) {
                $this->commandHandlerLoader->get($command)->handle($message);
            });
        }
    }
}
