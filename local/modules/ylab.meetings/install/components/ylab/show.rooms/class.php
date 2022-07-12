<?php
use CBitrixComponent;

class ShowRoomsComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $result = RoomTable::getList(array(
            'select' => array('ID', 'NAME', 'ACTIVITY'),
            'filter' => array('=ACTIVITY' => true)
        ));
        $arResult = $result->fetchAll();  //в $arResult передаются параметры доступных переговорных
    }
}