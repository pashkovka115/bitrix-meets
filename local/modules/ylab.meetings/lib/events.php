<?php

namespace Ylab\Meetings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;

/**
 * Class Events
 * @package    Ylab\Meetings
 */
class Events
{
    /**
     * @return bool
     */
    public static function OnAfterCalendarEntryAdd($event_id, $fields): bool
    {
        AddMessage2Log($fields, "ylab.meetings");
        return true;
    }

}