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
        return Loader::includeModule('ylab.meetings');
    }

    /**
     * @return mixed|void|null
     * @throws \Bitrix\Main\LoaderException
     * Запуск компонента
     */
    public function executeComponent()
    {
        if ($this->checkModules()){
            $auth = new Auth();
            echo $auth->authorization();
        }else{
            ShowMessage([
                "TYPE"=>"OK",
                "MESSAGE"=>Loc::getMessage('YLAB_MEETING_EDIT_ERROR_CHECK_COMPONENT')
            ]);
        }
    }

}
