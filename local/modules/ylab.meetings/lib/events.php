<?php

namespace Ylab\Meetings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Ylab\Meetings\Calendar\Event;

/**
 * Class Events
 * @package    Ylab\Meetings
 */
class Events
{
    /**
     * @return void
     */
    public static function OnAfterCalendarEntryAdd($event_id, $fields)
    {
        Event::ChangeDescriptionById($event_id);
    }

}