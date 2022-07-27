<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Calendar\Internals\TypeTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Ylab\Meetings\IntegrationTable;
use Ylab\Meetings\RoomTable;
use Ylab\Meetings\Calendar\CalendarType;


class YlabMeetingEdit extends CBitrixComponent
{
    protected $elementId = 'ELEMENT_ID';
    /**
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     * Проверяет подключение модулей необходимых для работы этого компонента
     */
    private function checkModules()
    {
        if (!Loader::includeModule('ylab.meetings') || !Loader::includeModule('calendar')) {
            throw new \Exception(Loc::getMessage('YLAB_MEETING_EDIT_ERROR_CHECK_COMPONENT'));
        }

        return true;
    }


    /**
     * @return CAllMain|CMain
     * Обёртка для удобства использования над глобальной переменной $APPLICATION
     */
    private function app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }


    /**
     * @param $params
     * @return array
     * Запускается при подготовке входных параметров
     */
    public function onPrepareComponentParams($params)
    {
        if (is_null($params[$this->elementId])) {
            $this->arParams[$this->elementId] = false;
        } else {
            $this->arParams[$this->elementId] = $params['ELEMENT_ID'] * 1;;
        }

        return $params;
    }


    /**
     * @return mixed|void|null
     * @throws \Bitrix\Main\LoaderException
     * Запуск компонента
     */
    public function executeComponent()
    {
        $this->checkModules();

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost()) {
            $this->handlerPost($request);
        } else {
            $this->handlerGet();
        }

        $this->includeComponentTemplate();
    }


    public function validatePost($params)
    {
        if (!$params['INTEGRATION_ID']) {
            setMessage('Не указана интеграция');
        }
        if (!$params['CALENDAR_TYPE_XML_ID']) {
            setMessage('Не указан символьный код календаря');
        }
        if (!$params['CALENDAR_TYPE_NAME']) {
            setMessage('Не указано имя типа календаря');
        }
        if (!$params['NAME']) {
            setMessage('Не указано имя комнаты');
        }

        if (!$params['INTEGRATION_ID'] or
            !$params['CALENDAR_TYPE_XML_ID'] or
            !$params['CALENDAR_TYPE_NAME'] or
            !$params['NAME']
        ){
            LocalRedirect($this->app()->GetCurPage());
        }
    }


    /**
     * @param $params
     * @return RoomTable|bool
     * обновляет комнату
     */
    public function updateRoom($params)
    {
        $xml_id = CalendarType::edit([
            'XML_ID' => $params['CALENDAR_TYPE_XML_ID'],
            'NAME' => $params['CALENDAR_TYPE_NAME'],
            'DESCRIPTION' => $params['CALENDAR_TYPE_DESCRIPTION']
        ]);
        $room = RoomTable::update($params['ID'] * 1, [
            'NAME' => htmlspecialcharsbx($params['NAME']),
            'ACTIVITY' => isset($params['ACTIVITY']) ? 'Y' : 'N',
            'INTEGRATION_ID' => $params['INTEGRATION_ID'] * 1,
            'CALENDAR_TYPE_XML_ID' => $xml_id
        ]);

        return $room;
    }


    /**
     * @param array $params
     * @return RoomTable|bool
     * сохранить комнату
     */
    public function addRoom(array $params)
    {
        if (CalendarType::hasItem($params['CALENDAR_TYPE_XML_ID'])){
            setMessage('Такой тип календаря уже существует');
            LocalRedirect($this->app()->GetCurPage());
        }
        $xml_id = CalendarType::add([
            'XML_ID' => $params['CALENDAR_TYPE_XML_ID'],
            'NAME' => $params['CALENDAR_TYPE_NAME'],
            'DESCRIPTION' => $params['CALENDAR_TYPE_DESCRIPTION']
        ]);

        $room = RoomTable::add([
            'NAME' => htmlspecialcharsbx($params['NAME']),
            'ACTIVITY' => isset($params['ACTIVITY']) ? 'Y' : 'N',
            'INTEGRATION_ID' => $params['INTEGRATION_ID'] * 1,
            'CALENDAR_TYPE_XML_ID' => $xml_id
        ]);

        return $room;
    }


    /**
     * @param $params
     * Удалить комнату. Надо передать id комнаты и id типа календаря
     */
    public function deleteRoom($params)
    {
        if (!$params['ID'] or !strlen($params['CALENDAR_TYPE_XML_ID'])){
            setMessage('Неизвестна комната или тип календаря');
            LocalRedirect($this->app()->GetCurPage());
        }
        RoomTable::delete($params[$this->elementId] * 1);
        CalendarType::delete($params['CALENDAR_TYPE_XML_ID']);
    }


    /**
     * @param $request
     * Обработка POST запроса
     */
    public function handlerPost($request)
    {
        $params = $request->toArray();

        $this->validatePost($params);

        if (bitrix_sessid_get() == 'sessid=' . $params['sessid']) {
            if (isset($params['delete_room'])){
                $this->deleteRoom($params);
            }
            if (isset($params['ID'])) {
                $room = $this->updateRoom($params);
                LocalRedirect($this->app()->GetCurPageParam('ELEMENT_ID=' . $params['ID']));
            } else {
                $room = $this->addRoom($params);
            }
        }

        if ($room->isSuccess()){
            setMessage('Удачно', 'success');
        }else{
            setMessage('Ошибка сохранения');
        }

        LocalRedirect($this->app()->GetCurPage());
    }


    /**
     * Обработка GET запроса
     */
    public function handlerGet()
    {
        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();

        $this->arResult['CALENDAR_TYPES'] = CalendarType::getAll();

        // если известен идентификатор достанем элемент
        if ($this->arParams[$this->elementId]) {
            $this->arResult['ITEM'] = RoomTable::getRowById($this->arParams[$this->elementId]);

            foreach ($this->arResult['CALENDAR_TYPES'] as $type){
                if ($type['XML_ID'] == $this->arResult['ITEM']['CALENDAR_TYPE_XML_ID']){
                    $this->arResult['ITEM']['CALENDAR_TYPE_NAME'] = $type['NAME'];
                    $this->arResult['ITEM']['CALENDAR_TYPE_DESCRIPTION'] = $type['DESCRIPTION'];
                    break;
                }
            }
        }
    }
}
