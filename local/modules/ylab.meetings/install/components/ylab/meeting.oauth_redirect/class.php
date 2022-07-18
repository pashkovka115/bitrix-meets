<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Ylab\Meeting\Zoom\Auth;


class YlabMeetingOauthRedirect extends CBitrixComponent
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
     * @return mixed|void|null
     * @throws \Bitrix\Main\LoaderException
     * Запуск компонента
     */
    public function executeComponent()
    {
        $this->checkModules();

        $auth = new Auth();
        echo $auth->authorization();
    }

}
