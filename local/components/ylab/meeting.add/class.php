<?php
namespace YLab\Components;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \CBitrixComponent;
use \CCalendarType;
use \CModule;
use \Exception;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Calendar\Internals\TypeTable;
use Ylab\Meetings\IntegrationTable;
use Ylab\Meetings\Calendar\CalendarType;


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
     * @return CAllMain|CMain
     * Обёртка для удобства использования над глобальной переменной $APPLICATION
     */
    private function app()
    {
        global $APPLICATION;
        return $APPLICATION;
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
        if(CModule::IncludeModule("calendar"))
        {
            $this->arResult['CALENDAR_TYPES'] = CCalendarType::GetList();
        }

        if ($request->getPost('action')) {

            if ($request->getPost('action') == 'submitadd') {

                $fields = [
                  'NAME' => $request->getPost('NAME'),
                  'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
                  'INTEGRATION_ID' => $request->getPost('INTEGRATION_ID'),
                  'CALENDAR_TYPE_XML_ID' => $request->getPost('CALENDAR_TYPE_XML_ID'),
                ];

                $addResult = $this->addRoom($fields);

                if (!$addResult->isSuccess()) {
                    $integrations = IntegrationTable::getList();
                    $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();
                    if(CModule::IncludeModule("calendar"))
                    {
                        $this->arResult['CALENDAR_TYPES'] = CCalendarType::GetList();
                    }
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
          'CALENDAR_TYPE_XML_ID' => $fields['CALENDAR_TYPE_XML_ID'],
        ));
    }


    /**
     * Обработка GET запроса
     */
    public function handlerGet()
    {
        $integrations = IntegrationTable::getList();
        $this->arResult['INTEGRATIONS'] = $integrations->fetchAll();

        if(CModule::IncludeModule("calendar"))
        {
            $this->arResult['CALENDAR_TYPES'] = CCalendarType::GetList();
        }


    }
}
