<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();


if (!check_bitrix_sessid() || !$request->isPost())
    return;

$action = $request->getPost('action');
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin.php';
global $APPLICATION;
$APPLICATION->IncludeComponent(
    'ylab:integrations.list',
    'grid',
    [
        'ACTION' => [
            'NAME' => $action,
        ],
        'LIST_ID' => 'integrations_list',
        'ORM_NAME' => 'IntegrationTable',
        'COLUMN_FIELDS' => [
            0 => 'ID',
            1 => 'NAME',
            2 => 'ACTIVITY',
            3 => 'INTEGRATION_REF',
            4 => 'LOGIN',
            5 => 'PASSWORD',
        ],
        'FILTER_FIELDS' => array(
            0 => 'ID',
            1 => 'NAME',
            2 => 'ACTIVITY',
            3 => 'INTEGRATION_REF',
            4 => 'LOGIN',
            5 => 'PASSWORD',
        ),

    ]
);