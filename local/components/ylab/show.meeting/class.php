<?php

use Bitrix\Main\Loader;
use Ylab\Meetings\RoomTable;


/**
 * Class ShowRoomsComponent
 *
 * @package YLab\Components
 */

class ShowRoomsComponent extends CBitrixComponent
{
    /**
     * Method executeComponent
     *
     * @param array $arResult
     * @return mixed|void
     * @throws Exception
     */

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