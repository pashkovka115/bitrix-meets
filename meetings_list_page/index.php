<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle("");
?><? $APPLICATION->IncludeComponent(
  "ylab:meetings.list",
  "grid",
  array(
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
    "LIST_ID" => "rooms_list",
    "ORM_NAME" => "RoomTable",
    "COMPONENT_TEMPLATE" => "grid"
  ),
  false
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>