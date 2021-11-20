<?php

namespace Powernic\Bot;

use Powernic\Bot\Framework\CallbackHandler\CallbackHandlerLoader;
use Powernic\Bot\Framework\CommandHandler\CommandHandlerLoader;
use Powernic\Bot\Framework\TextHandler\CallbackTextHandler;
use Symfony\Component\HttpKernel\KernelInterface;
use TelegramBot\Api\Client;
use TelegramBot\Api\InvalidJsonException;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

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
     * @throws InvalidJsonException
     */
    public function boot()
    {
        $this->kernel->boot();
        $this->registerBotHandlers();
        $this->getClient()->run();
    }

    private function getClient(): Client
    {
        return $this->kernel->getContainer()->get(Client::class);
    }

    private function registerBotHandlers()
    {
        $this->registerCommandHandler();
        $this->registerCallbackHandler();
        $this->registerTextHandler();
    }

    private function registerCommandHandler()
    {
        $container = $this->kernel->getContainer();
        if ($container->has(CommandHandlerLoader::class)) {
            $commandHandlerLoader = $container->get(CommandHandlerLoader::class);
            $commands = $commandHandlerLoader->getNames();
            foreach ($commands as $command) {
                $this->getClient()->command(
                    $command,
                    function (Message $message) use ($commandHandlerLoader, $command) {
                        $commandHandlerLoader->get($command)->handle($message);
                    }
                );
            }
        }
    }

    private function registerCallbackHandler()
    {
        $container = $this->kernel->getContainer();
        if ($container->has(CallbackHandlerLoader::class)) {
            $callbackHandlerLoader = $container->get(CallbackHandlerLoader::class);
            $callbacks = $callbackHandlerLoader->getRefs();
            $this->getClient()->callbackQuery(
                function (CallbackQuery $callbackQuery) use ($callbackHandlerLoader, $callbacks) {
                    foreach ($callbacks as $callbackId => $callbackHandler) {
                        if (
                            $callbackId === $callbackQuery->getData()
                            || $callbackHandler::check($callbackId, $callbackQuery->getData())
                        ) {
                            $callbackHandlerLoader->get($callbackId)->setQuery($callbackQuery)->handle();
                            break;
                        }
                    }
                }
            );
        }
    }

    private function registerTextHandler()
    {
        $container = $this->kernel->getContainer();
        if ($container->has(CallbackTextHandler::class)) {
            $callbackTextHandler = $container->get(CallbackTextHandler::class);
            $this->getClient()->on(function (Update $update) use ($callbackTextHandler) {
                $callbackTextHandler->handle($update);
            }, function () {
                return true;
            });
        }
    }
}
