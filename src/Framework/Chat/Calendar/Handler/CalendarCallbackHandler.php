<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Handler;

use DateTime;
use Powernic\Bot\Framework\Chat\Calendar\Selector\DayPeriodEnum;
use Powernic\Bot\Framework\Chat\Calendar\Selector\Selector;
use Powernic\Bot\Framework\Chat\Calendar\Selector\SelectorFactory;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Powernic\Bot\Framework\Handler\Callback\CallbackPrefixer;
use Powernic\Bot\Framework\Handler\Resolver\CallbackHandlerResolver;
use Powernic\Bot\Framework\Handler\Resolver\ContainerHandlerResolver;
use TelegramBot\Api\BotApi;

class CalendarCallbackHandler extends CallbackHandler
{
    private ContainerHandlerResolver $containerHandlerResolver;
    private SelectorFactory $selectorFactory;

    public function __construct(
        BotApi $bot,
        ContainerHandlerResolver $containerHandlerResolver,
        SelectorFactory $selectorFactory
    ) {
        $this->containerHandlerResolver = $containerHandlerResolver;
        $this->selectorFactory = $selectorFactory;
        parent::__construct($bot);
    }

    public function handle(): void
    {
        if ($this->isFinish()) {
            $this->runTargetHandler();
        } else {
            $this->showCalendar();
        }
    }

    private function showCalendar()
    {
        $selector = $this->getSelector($this->getRoute());
        $this->sendResponse($selector->getMessage(), $selector->getButtons(), true);
    }

    private function getPrefix(): string
    {
        $prefixer = new CallbackPrefixer($this->message);
        return $prefixer->getPrefix();
    }

    private function getSelector(string $calendarRoute): Selector
    {
        $year = (int)$this->getParameter('year');
        $month = (int)$this->getParameter('month');
        $day = (int)$this->getParameter('day');
        return $this->selectorFactory->create($calendarRoute, $year, $month, $day);
    }

    private function isFinish(): bool
    {
        $year = (int)$this->getParameter('year');
        $month = (int)$this->getParameter('month');
        $day = (int)$this->getParameter('day');
        $period = (int)$this->getParameter('period');
        if ($year && $month && $day && $period) {
            return true;
        }
        return false;
    }

    private function runTargetHandler(): void
    {
        /** @var CallbackHandlerResolver $handlerResolver */
        $handlerResolver = $this->containerHandlerResolver->getHandlerResolver(CallbackHandlerResolver::class);
        $handler = $handlerResolver->matchHandler($this->getPrefix(), $this->message);
        if ($handler instanceof DateIntervalHandlerInterface) {
            $year = (int)$this->getParameter('year');
            $month = (int)$this->getParameter('month');
            $day = (int)$this->getParameter('day');
            $period = (int)$this->getParameter('period');
            $startHour = 0;
            $endHour = 12;
            if (DayPeriodEnum::from($period) == DayPeriodEnum::PM) {
                $startHour = 12;
                $endHour = 24;
            }
            $handler->handleDateInterval(
                DateTime::createFromFormat('d/m/Y H', "{$day}/{$month}/{$year} {$startHour}"),
                DateTime::createFromFormat('d/m/Y H', "{$day}/{$month}/{$year} {$endHour}")
            );
        }
    }
}
