<?php

namespace Ylab\Meetings\Controller;

use Bitrix\Main\Engine\Controller;
use CUtil;
use Ylab\Meetings\IntegrationTable;

class IntegrationController extends Controller
{

    /**
     * @param array $fields
     * @return string|array
     * @throws \Exception
     */
    public static function addAction(array $fields)
    {
        $addResult = IntegrationTable::add(array(
            'NAME' => $fields['NAME'],
            'ACTIVITY' => $fields['ACTIVITY'],
            'INTEGRATION_REF' => $fields['INTEGRATION_REF'],
            'LOGIN' => $fields['LOGIN'],
            'PASSWORD' => $fields['PASSWORD']
        ));
        AddMessage2Log($addResult->getErrorMessages());
        if ($addResult->isSuccess()) {
            return CUtil::PhpToJSObject([
                'IS_SUCCESS' => true,
            ]);
        } else {
            return [
                'IS_SUCCESS' => false,
                'ERROR_MESSAGES' => $addResult->getErrorMessages(),
            ];
        }
    }

    public function updateAction($id, $fields): ?array
    {

    }

    public function deleteAction($id)
    {

    }
}