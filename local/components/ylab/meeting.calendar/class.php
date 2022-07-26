<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Ylab\Meetings\RoomTable;

/**
 * Class YlabMeetingCalendarComponent
 *
 * @package YLab\Components
 */

class YlabMeetingCalendarComponent extends CBitrixComponent
{
    /**
     * Method getMeetingList
     *
     * @return array $arResult
     * @throws Exception
     */

    public function getMeetingList(){
        $result = RoomTable::getList(array(
            'select' => array('ID', 'NAME','CALENDAR_TYPE_XML_ID')
        ));
        $this->arResult["MEETING_LIST"] = $result->fetchAll();
    }

    /**
     * Method executeComponent
     *
     * @param array $request
     * @return array $arResult
     * @throws Exception
     */

    public function executeComponent()
    {
        if (Loader::IncludeModule('ylab.meetings')) {
            $request = Context::getCurrent()->getRequest();
            $value = $request->getQuery("calendar_type");
            if ($value){
                $this->arResult["CALENDAR_TYPE"] = $value;
            }
            else{
                $this->arResult["CALENDAR_TYPE"] = "user";
            }
        }
        $this->getMeetingList();
        $this->includeComponentTemplate();
    }
}
?>

