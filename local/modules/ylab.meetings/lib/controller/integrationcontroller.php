<?php

namespace Ylab\Meetings\Controller;

use Bitrix\Main\Engine\Controller;
use Ylab\Meetings\IntegrationTable;

class IntegrationController extends Controller
{

    /**
     * @param array $fields
     * @return \Bitrix\Main\ORM\Data\AddResult|string
     * @throws \Exception
     */
    public static function addAction(array $fields)
    {
        $result = IntegrationTable::add(array(
            'NAME' => $fields['NAME'],
            'ACTIVITY' => $fields['ACTIVITY'],
            'INTEGRATION_REF' => $fields['INTEGRATION_REF'],
            'LOGIN' => $fields['LOGIN'],
            'PASSWORD' => $fields['PASSWORD']
        ));
        AddMessage2Log($result->getErrorMessages());
        return $result;
    }

    public function updateAction($id, $fields): ?array
    {

    }

    public function deleteAction($id)
    {

    }
}