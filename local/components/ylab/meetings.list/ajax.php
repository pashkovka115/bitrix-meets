<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid())
    return;

$action = $request->get('action');

$id = $action == 'edit_burger' ? $request->get('ID')
  : ($action == 'delete_burger' ? $request->getPost('ID') : null);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
global $APPLICATION;


if ($action == 'submitadd') {
    $APPLICATION->IncludeComponent(
      "ylab:meeting.add",
      "",
      array()
    );
}

if ($action == 'submitedit') {
    $APPLICATION->IncludeComponent(
      "ylab:meeting.edit",
      "",
      array(
        "ELEMENT_ID" => $_REQUEST["ID"],
      ),
    );
}

$APPLICATION->IncludeComponent(
  "ylab:meetings.list",
  "grid",
  array(
    'ACTION' => array(
      'NAME' => $action,
      'ID' => $id,
    ),
    "AJAX_MODE" => "Y",
    "LIST_ID" => "rooms_list",
    "ORM_NAME" => "RoomTable",
    "REPOSITORY" => "RoomRepository",
    "COMPONENT_TEMPLATE" => "grid",
    "COLUMN_FIELDS" => array(
      0 => "ID",
      1 => "NAME",
      2 => "ACTIVITY",
      3 => "INTEGRATION.NAME",
      4 => "CALENDAR_TYPE_XML_ID",
    ),
    "FILTER_FIELDS" => array(
      0 => "ID",
      1 => "NAME",
      2 => "ACTIVITY",
    ),
  ),
  false
);