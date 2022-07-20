<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Ylab\Meetings\IntegrationTable;
use Ylab\Meetings\RoomTable;


class YlabMeetingEdit extends CBitrixComponent
{
    /**
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     * Проверяет подключение модулей необходимых для работы этого компонента
     */
    private function checkModules()
    {
        if (!Loader::includeModule('ylab.meetings')) {
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
        $this->arResult['ERRORS'] = [];

        if (is_null($params['ELEMENT_ID'])) {
            $this->arParams['ELEMENT_ID'] = false;
        } else {
            $this->arParams['ELEMENT_ID'] = $params['ELEMENT_ID'] * 1;;
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


    /**
     * @param $request
     * Обработка POST запроса
     */
    public function handlerPost($request)
    {
        $params = $request->toArray();

        if (!isset($params['INTEGRATION_ID'])) {
            ShowMessage(array("TYPE" => "ERROR ", "MESSAGE" => "Не указана интеграция"));
            LocalRedirect($this->app()->GetCurPage());
        }

        if (bitrix_sessid_get() == 'sessid=' . $params['sessid']) {
            if (isset($params['ID'])) {
                RoomTable::update($params['ID'] * 1, [
                    'NAME' => htmlspecialcharsbx($params['NAME']),
                    'ACTIVITY' => isset($params['ACTIVITY']) ? 'Y' : 'N',
                    'INTEGRATION_ID' => $params['INTEGRATION_ID'] * 1
                ]);

                LocalRedirect($this->app()->GetCurPageParam('ELEMENT_ID=' . $params['ID']));
            } else {
                $room = RoomTable::add([
                    'NAME' => htmlspecialcharsbx($params['NAME']),
                    'ACTIVITY' => isset($params['ACTIVITY']) ? 'Y' : 'N',
                    'INTEGRATION_ID' => $params['INTEGRATION_ID'] * 1
                ]);

                if ($room->isSuccess()){
                    setMessage("Сохранено", 'success');
                }else{
                    setMessage("Во время добавления произошла ошибка");
                }
            }
        }

        LocalRedirect($this->app()->GetCurPage());
    }


    /**
     * Обработка GET запроса
     */
    public function handlerGet()
    {
        if ($this->arParams['ELEMENT_ID']) {
            $this->arResult['ITEM'] = RoomTable::getRowById($this->arParams['ELEMENT_ID']);
        }

        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();
    }
}
