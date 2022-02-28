<?php

namespace Powernic\Bot\Framework\Handler\Callback;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\AvailableRouteInterface;
use Powernic\Bot\Framework\Handler\RouteHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

abstract class CallbackHandler extends RouteHandler implements AvailableRouteInterface, AvailableMessageInterface
{
    private BotApi $bot;

    public function __construct(BotApi $bot)
    {
        $this->bot = $bot;
    }

    protected function sendResponse(string $message, array $buttons = [], bool $withContext = false): void
    {
        $chatId = $this->message->getChat()->getId();
        $messageId = $this->message->getMessageId();
        $context = CallbackPrefixer::encodePrefix($this->getRoute());
        $this->bot->editMessageText(
            $chatId,
            $messageId,
            $message . ($withContext ? $context : ""),
            $withContext ? 'HTML' : null
        );
        if(!empty($buttons)) {
            $this->bot->editMessageReplyMarkup($chatId, $messageId, new InlineKeyboardMarkup($buttons));
        }
    }

}
