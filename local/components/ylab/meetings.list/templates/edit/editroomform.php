<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php
$APPLICATION->IncludeComponent(
  "ylab:meeting.edit",
  "",
  array(
    "ELEMENT_ID" => $_REQUEST["ID"]
  )
);