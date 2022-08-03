<?php

namespace Ylab\Meetings\Controller;

use Bitrix\Main\Engine\Controller;
use Ylab\Meetings\IntegrationTable;

/**
 * Контроллер для обработки ajax запросов компонента интеграции
 *
 * @package ylab
 * @subpackage meetings\controller
 */
class IntegrationController extends Controller
{

    /**
     * Метод обрабатывающий действие добавления
     *
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

    /**
     * Метод обрабатывающий действие изменения
     *
     * @param $id
     * @param $fields
     * @return array|bool[]
     * @throws \Exception
     */
    public function updateAction($id, $fields)
    {

        $updateResult = IntegrationTable::update($id, array(
            'NAME' => $fields['NAME'],
            'ACTIVITY' => $fields['ACTIVITY'],
            'INTEGRATION_REF' => $fields['INTEGRATION_REF'],
            'LOGIN' => $fields['LOGIN'],
            'PASSWORD' => $fields['PASSWORD']
        ));

        if ($updateResult->isSuccess()) {
            return [
                'IS_SUCCESS' => true,
            ];
        } else {
            return [
                'IS_SUCCESS' => false,
                'ERROR_MESSAGES' => $updateResult->getErrorMessages(),
            ];
        }
    }


    /**
     * Метод обрабатывающий действие удаления
     *
     * @param $id
     * @return bool[]
     * @throws \Exception
     */
    public function deleteAction($id): array
    {
        /** @var Bitrix\Main\Entity\UpdateResult $deleteResult */
        if (is_array($id)) {
            foreach ($id as $item)
                $deleteResult = IntegrationTable::delete($item);
        } else {
            $deleteResult = IntegrationTable::delete($id);
        }
        if ($deleteResult->isSuccess()) {
            return ['IS_SUCCESS' => true];
        } else {
            return [
                'IS_SUCCESS' => false,
                'ERROR_MESSAGES' => $deleteResult->getErrorMessages(),
            ];

        }
    }

    /**
     * Метод обрабатывающий действие вывода полей
     *
     * @param $id
     * @return array|false[]
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getFieldsAction($id): array
    {

        $res = IntegrationTable::getList([
            'filter' => ['ID' => $id],
            'select' => [
                "*",
            ]]);

        foreach ($res->fetchAll() as $row) {
            $fields = [
                "ID" => $row['ID'],
                "NAME" => $row['NAME'],
                "ACTIVITY" => $row['ACTIVITY'],
                "INTEGRATION_REF" => $row['INTEGRATION_REF'],
                "LOGIN" => $row['LOGIN'],
                "PASSWORD" => $row['PASSWORD'],
            ];
        }
        if ($fields) {
            return [
                'IS_SUCCESS' => true,
                'FIELDS' => $fields,
            ];
        } else {
            return [
                'IS_SUCCESS' => false,
            ];
        }
    }
}