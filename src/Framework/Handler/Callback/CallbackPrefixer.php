<?php

namespace Powernic\Bot\Framework\Handler\Callback;

use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\MessageEntity;

class CallbackPrefixer
{
    private static string $linkPrefix = 'tg://btn/';
    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getPrefix(): ?string
    {
        $entities = $this->message->getEntities();
        if (isset($entities)) {
            /** @var MessageEntity $entity */
            foreach ($entities as $entity) {
                if ($entity->getType() === 'text_link') {
                    if (str_contains($entity->getUrl(), self::$linkPrefix)) {
                        $encodedPrefix = str_replace(self::$linkPrefix, '', $entity->getUrl());
                        return base64_decode($encodedPrefix);
                    }
                }
            }
        }
        return null;
    }

    public static function encodePrefix(string $route): string
    {
        return "<a href=\"" . self::$linkPrefix . base64_encode($route) . "\">&#8203;</a>";
    }
}
