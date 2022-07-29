<?php

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

if (Loader::includeModule("ylab.meetings")) {
    EventManager::getInstance()
        ->registerEventHandler(
            'calendar',
            'OnAfterCalendarEntryAdd',
            'ylab.meetings',
            '\\Ylab\\Meetings\\Events',
            'OnAfterCalendarEntryAdd'
        );
    EventManager::getInstance()
        ->registerEventHandler(
            'main',
            'OnEpilog',
            'ylab.meetings',
            '\\Ylab\\Meetings\\Events',
            'JSCoreInit'
        );
}