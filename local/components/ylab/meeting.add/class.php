<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Ylab\Meetings\IntegrationTable;


class YlabMeetingAdd extends CBitrixComponent
{

    /**
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     * Проверяет подключение модулей необходимых для работы этого компонента
     */
    private function checkModules()
    {
        if (!Loader::includeModule('ylab.meetings')) {
            throw new \Exception(Loc::getMessage('YLAB_MEETING_ADD_ERROR_CHECK_COMPONENT'));
        }

        return true;
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

        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();

        if ($request->getPost('action')) {

            if ($request->getPost('action') == 'submitadd') {

                $fields = [
                  'NAME' => $request->getPost('NAME'),
                  'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
                  'INTEGRATION_ID' => $request->getPost('INTEGRATION_ID'),
                ];

                $addResult = $this->addRoom($fields);

                if (!$addResult->isSuccess()) {
                    $integrations = IntegrationTable::getList();
                    $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();
                    $this->arResult['SUBMIT_ERROR'] = $addResult->getErrorMessages();
                    return;
                }

            }
        }

    }


    /**
     * @param array $fields
     * @return \Bitrix\Main\ORM\Data\AddResult
     * @throws Exception
     */

    private function addRoom(array $fields): \Bitrix\Main\ORM\Data\AddResult
    {

        return \Ylab\Meetings\RoomTable::add(array(
          'NAME' => $fields['NAME'],
          'ACTIVITY' => $fields['ACTIVITY'],
          'INTEGRATION_ID' => $fields['INTEGRATION_ID'],
        ));
    }


    /**
     * Обработка GET запроса
     */
    public function handlerGet()
    {
        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();
    }
}
