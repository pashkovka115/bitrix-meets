<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin.php';

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();


if (!check_bitrix_sessid() || !$request->isPost())
    return;

$action = $request->getPost('action');

if ($action == 'submitadd') {
    $fields = [
        'NAME' => $request->getPost('NAME'),
        'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
        'INTEGRATION_REF' => $request->getPost('INTEGRATION_REF'),
        'LOGIN' => $request->getPost('LOGIN'),
        'PASSWORD' => $request->getPost('PASSWORD'),
    ];
}
if ($action == 'submitedit') {
    $fields = ([
        $request->getPost('ID') =>
            [
                'NAME' => $request->getPost('NAME'),
                'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
                'INTEGRATION_REF' => $request->getPost('INTEGRATION_REF'),
                'LOGIN' => $request->getPost('LOGIN'),
                'PASSWORD' => $request->getPost('PASSWORD'),
            ]
    ]);
}


if ($action == 'edit_burger') {
    $id = $request->getPost('id');
}
if ($action == 'delete_burger') {
    $id = ($request->getPost('id'));
}


global $APPLICATION;
$APPLICATION->IncludeComponent(
    'ylab:integrations.list',
    'grid',
    [
        'ACTION' => [
            'NAME' => $action,
            'FIELDS' => $fields,
            'ID' => $id,
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