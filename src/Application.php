<?php

namespace Powernic\Bot;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;

final class Application
{
    private Client $client;
    private BotApi $bot;

    public function __construct(Client $client, BotApi $bot)
    {
        $this->client = $client;
        $this->bot = $bot;
    }

    public function run(Request $request): Response
    {
        try {
            $this->client->command('emias-reg', function ($message) {
                $chatId = $message->getChat()->getId();
                $this->bot->sendMessage($chatId, 'pong!');
            });
            $this->client->run();
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }

        return new Response();
    }
}
