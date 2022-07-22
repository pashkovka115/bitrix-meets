<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

/**
 * Class YlabMeetingCalendarComponent
 *
 * @package YLab\Components
 */

class YlabMeetingCalendarComponent extends CBitrixComponent
{
    /**
     * Method executeComponent
     *
     * @param array $request
     * @return bool
     * @throws Exception
     */

    public function executeComponent()
    {
        if (Loader::IncludeModule('ylab.meetings')) {
            $request = Context::getCurrent()->getRequest();
            $value = $request->getQuery("calendar_type");
            if ($value == "user"){
                return true;
            }
        }
        $this->includeComponentTemplate();
    }
}
?>

