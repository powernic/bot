<?php

namespace Powernic\Bot;

use Powernic\Bot\CallbackHandler\CallbackHandlerLoader;
use Powernic\Bot\CommandHandler\CommandHandlerLoader;
use Powernic\Bot\Entity\Chat\User;
use Powernic\Bot\TextHandler\CallbackTextHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\InvalidJsonException;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

final class Application
{
    private Client $client;
    private CommandHandlerLoader $commandHandlerLoader;
    private CallbackHandlerLoader $callbackHandlerLoader;
    private CallbackTextHandler $callbackTextHandler;

    public function __construct(
        Client $client,
        CallbackTextHandler $callbackTextHandler,
        CommandHandlerLoader $commandHandlerLoader,
        CallbackHandlerLoader $callbackHandlerLoader,
    ) {
        $this->client = $client;
        $this->commandHandlerLoader = $commandHandlerLoader;
        $this->callbackHandlerLoader = $callbackHandlerLoader;
        $this->callbackTextHandler = $callbackTextHandler;
    }

    /**
     * @throws InvalidJsonException
     */
    public function run()
    {
        $this->addBotHandlers();
        $this->client->run();
    }

    private function addBotHandlers()
    {
        $this->addCommandHandler();
        $this->addCallbackHandler();
        $this->addTextHandler();
    }

    private function addCommandHandler()
    {
        $commands = $this->commandHandlerLoader->getNames();
        foreach ($commands as $command) {
            $this->client->command($command, function (Message $message) use ($command) {
                $this->commandHandlerLoader->get($command)->handle($message);
            });
        }
    }

    private function addCallbackHandler()
    {
        $callbacks = $this->callbackHandlerLoader->getRefs();
        $this->client->callbackQuery(function (CallbackQuery $callbackQuery) use ($callbacks) {
            foreach ($callbacks as $callbackId => $callbackHandler) {
                if (
                    $callbackId === $callbackQuery->getData()
                    || $callbackHandler::check($callbackId, $callbackQuery->getData())
                ) {
                    $this->callbackHandlerLoader->get($callbackId)->setQuery($callbackQuery)->handle();
                }
            }
        });
    }

    private function addTextHandler()
    {
        $this->client->on(function (Update $update) {
            $this->callbackTextHandler->handle($update);
        }, function () {
            return true;
        });
    }
}
