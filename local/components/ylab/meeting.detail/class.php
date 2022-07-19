<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Ylab\Meetings\RoomTable;

/**
 * Class YlabMeetingDetailComponent
 *
 * @package YLab\Components
 */

class YlabMeetingDetailComponent extends CBitrixComponent
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
            'select' => array('ID', 'NAME', 'ACTIVITY', 'MEET_DATE'),
            'filter' => array('=ID' => $this->arParams['ID'])
        ));
        $this->arResult = $result->fetchAll();

        $this->includeComponentTemplate();
    }
}