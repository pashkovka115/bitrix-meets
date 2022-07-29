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
        if ($addResult->isSuccess()) {
            return [
                'IS_SUCCESS' => true,
            ];
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

    /**
     * @param int|array $id
     * @throws \Exception
     */
    public function deleteAction($id): array
    {
        AddMessage2Log($id);
        /** @var Bitrix\Main\Entity\UpdateResult $result */
        if (is_array($id)) {
            foreach ($id as $item)
                $result = IntegrationTable::delete($item);
        } else {
            $result = IntegrationTable::delete($id);
        }
        if ($result->isSuccess()) {
            return ['IS_SUCCESS' => true];
        }
    }
}