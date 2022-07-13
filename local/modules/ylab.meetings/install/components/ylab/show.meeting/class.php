<?php

use Bitrix\Main\Loader;
use Ylab\Meetings\RoomTable;

class ShowRoomsComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        Loader::includeModule('ylab.meetings');

        $result = RoomTable::getList(array(
            'select' => array('ID', 'NAME', 'ACTIVITY'),
            'filter' => array('=ACTIVITY' => true)
        ));
        $this->arResult = $result->fetchAll();  //в $arResult передаются параметры доступных переговорных

        $this->includeComponentTemplate();
    }
}