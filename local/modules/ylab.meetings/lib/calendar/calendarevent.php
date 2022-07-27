<?php

namespace Ylab\Meetings\Calendar;

use Bitrix\Highloadblock as HL;
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

            if ($event["CAL_TYPE"] == "user") {
                self::addEvent([
                    'UF_ID_EVENT_CALENDAR' => $event['ID'],
                    'UF_ID_ROOM' => rand(100, 10000),
                    'UF_CALENDAR_TYPE_XML_ID' => $event['CAL_TYPE'],
                    'UF_URL_START' => 'http://' . bin2hex(random_bytes(10)) . '.site.com',
                ]);

                CCalendarEvent::edit(["arFields" => $event]);
            }
        }
    }


    public static function addEvent(array $data)
    {
        Loader::includeModule('highloadblock');

        $id_hlbl = 2; // ID highloadblock
        $hlblock = HL\HighloadBlockTable::getById($id_hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $class = $entity->getDataClass();

        $class::add($data);
    }
}
