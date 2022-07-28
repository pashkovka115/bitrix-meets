<?php

namespace Ylab\Meetings\Calendar;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;
use CCalendarEvent;
use Ylab\Meetings\Integrations\IntegrationBase;
use Ylab\Meetings\RoomTable;

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
                $room = RoomTable::getList([
                    'select' => ['ID', 'INTEGRATION_ID'],
                    'filter' => ['CALENDAR_TYPE_XML_ID' => 'user'],
                    'limit' => 1
                ])->fetch();

                $link = 'error';
                $integration = IntegrationBase::init($room['INTEGRATION_ID']);
                if ($integration){
                    $std = $integration->getLink();
                    $link = $std->start_url;
                }

                self::addEvent([
                    'UF_ID_EVENT_CALENDAR' => $event['ID'] * 1,
                    'UF_ID_ROOM' => $room['ID'],
                    'UF_CALENDAR_TYPE_XML_ID' => $event['CAL_TYPE'],
                    'UF_URL_START' => $link,
                ]);

                CCalendarEvent::edit(["arFields" => $event]);
            }
        }
    }


    public static function addEvent(array $data)
    {
        Loader::includeModule('highloadblock');

        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'YlabMeetingsZoom']
        ])->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $class = $entity->getDataClass();

        $class::add($data);
    }

}