<?php

namespace Ylab\Meetings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Ylab\Meetings\Calendar\CalendarEvent;
use Bitrix\Main\Loader;
use CJSCore;

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
        CalendarEvent::ChangeDescriptionById($event_id);
    }

    /**
     * @return void
     */
    public static function JSCoreInit()
    {
        CJSCore::RegisterExt(
            'core_js',
            array(
                'js' => '/local/modules/ylab.meetings/js/core.js',
                'lang' => '/local/modules/ylab.meetings/lang/ru/js/core_js.php'
            )
        );
        CJSCore::Init(array('core_js'));
    }

}