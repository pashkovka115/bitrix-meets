<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid() || !$request->isPost())
    return;

$action = $request->getPost('action');
$fields = $action == 'submitadd' ? [
  'NAME' => $request->getPost('NAME'),
  'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
  'INTEGRATION_ID' => $request->getPost('INTEGRATION_ID'),
] : ($action == 'submitedit' ? [
  $request->getPost('ID') =>
    [
      'NAME' => $request->getPost('NAME'),
      'ACTIVITY' => $request->getPost('ACTIVITY') === 'Y',
      'INTEGRATION_ID' => $request->getPost('INTEGRATION_ID'),
    ]
] : null);

$id = $action == 'edit_burger' ? $request->getPost('ID')
  : ($action == 'delete_burger' ? $request->getPost('ID') : null);


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
global $APPLICATION;


$APPLICATION->IncludeComponent(
  "ylab:meetings.list",
  "grid",
  array(
    'ACTION' => array(
      'NAME' => $action,
      'FIELDS' => $fields,
      'ID' => $id,
    ),
    "AJAX_MODE" => "Y",
    "LIST_ID" => "rooms_list",
    "ORM_NAME" => "RoomTable",
    "COMPONENT_TEMPLATE" => "grid",
    "COLUMN_FIELDS" => array(
      0 => "ID",
      1 => "NAME",
      2 => "ACTIVITY",
      3 => "INTEGRATION.NAME",
    ),
    "FILTER_FIELDS" => array(
      0 => "ID",
      1 => "NAME",
      2 => "ACTIVITY",
    ),
  ),
  false
);
