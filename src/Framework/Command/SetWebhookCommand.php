<?php

namespace Powernic\Bot\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TelegramBot\Api\BotApi;

class SetWebhookCommand extends Command
{
    protected static $defaultName = 'app:bot:set-webhook';
    private BotApi $bot;
    private string $hookUrl;

    public function __construct(BotApi $bot, string $hookUrl)
    {
        $this->bot = $bot;
        $this->hookUrl = $hookUrl;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check all Emias subscriptions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
             $this->bot->setWebhook($this->hookUrl);
        } catch (\JsonMapper_Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
