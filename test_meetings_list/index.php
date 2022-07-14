<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
?>

<?php
$APPLICATION->IncludeComponent(
  'ylab:meetings.list',
  'grid',
  [
    'LIST_ID' => 'rooms_list',
    'ORM_NAME' => 'RoomTable',
    'COLUMN_FIELDS' => array(
      0 => 'ID',
      1 => 'NAME',
      2 => 'ACTIVITY',
      3 => 'INTEGRATION_ID',
    ),
    'FILTER_FIELDS' => array(
      0 => 'ID',
      1 => 'NAME',
      2 => 'ACTIVITY',
    )
  ]
);
?>

<?php
$APPLICATION->IncludeComponent(
  'ylab:meetings.list',
  'grid',
  [
    'LIST_ID' => 'integrations_list',
    'ORM_NAME' => 'IntegrationTable',
    'COLUMN_FIELDS' => array(
      0 => 'ID',
      1 => 'NAME',
      2 => 'ACTIVITY',
      3 => 'INTEGRATION_REF',
      4 => 'LOGIN',
      5 => 'PASSWORD',
    ),
    'FILTER_FIELDS' => array(
      0 => 'ID',
      1 => 'NAME',
      2 => 'ACTIVITY',
    )
  ]
);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

