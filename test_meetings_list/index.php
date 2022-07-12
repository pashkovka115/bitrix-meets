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
    'COLUMN_FIELDS' => [
      'ID', 'NAME', 'ACTIVITY',
      'INTEGRATION_ALIAS' => 'INTEGRATION.NAME',
    ]
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
    'COLUMN_FIELDS' => [
      'ID', 'NAME', 'ACTIVITY',
      'INTEGRATION_REF',
      'LOGIN', 'PASSWORD',
    ]
  ]
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

