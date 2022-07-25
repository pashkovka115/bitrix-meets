<?php

namespace Ylab\Meetings\Calendar;

use Bitrix\Main\Loader;
use CCalendarEvent;

/**
 * Class Event
 * @package    Ylab\Meetings\Calendar
 */
class CalendarEvent
{
    /**
     * @return void
     */
    public static function ChangeDescriptionById($id)
    {
        if (Loader::includeModule("calendar")) {
            $event = CCalendarEvent::GetById($id);
            if($event["CAL_TYPE"] == "user") {
                $event["DESCRIPTION"] = $event["NAME"];
                CCalendarEvent::edit(array("arFields" => $event));
            }
        }

    }

}