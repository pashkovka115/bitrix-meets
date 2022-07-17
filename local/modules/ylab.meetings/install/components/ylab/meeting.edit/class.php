<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use \Ylab\Meetings\RoomTable;
use \Ylab\Meetings\IntegrationTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


class YlabMeetingEdit extends CBitrixComponent
{
    private function checkModules()
    {
        if (!Loader::includeModule('ylab.meetings')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }


    private function app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }


    public function onPrepareComponentParams($params)
    {
        if (is_null($params['ELEMENT_ID'])) {
            $this->arParams['ELEMENT_ID'] = false;
        } else {
            $this->arParams['ELEMENT_ID'] = $params['ELEMENT_ID'] * 1;;
        }

        return $params;
    }


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
                RoomTable::add([
                    'NAME' => htmlspecialcharsbx($params['NAME']),
                    'ACTIVITY' => isset($params['ACTIVITY']) ? 'Y' : 'N',
                    'INTEGRATION_ID' => $params['INTEGRATION_ID'] * 1
                ]);
            }
        }

        LocalRedirect($this->app()->GetCurPage());
    }


    public function handlerGet()
    {
        if ($this->arParams['ELEMENT_ID']) {
            $this->arResult['ITEM'] = RoomTable::getRowById($this->arParams['ELEMENT_ID']);
        }

        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();
    }
}
