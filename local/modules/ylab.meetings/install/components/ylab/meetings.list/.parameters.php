<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

//формирование массива параметров
$arComponentParameters = array(
  "GROUPS" => array(
    "OPTIONS" => array(
      "NAME" => Loc::getMessage('YLAB_MEETING_LIST_OPTIONS_NAME'),
      "SORT" => "300",
    ),
  ),
  'PARAMETERS' => array(
    "LIST_ID"    =>  array(
      "PARENT"    =>  "OPTIONS",
      "NAME"      =>  Loc::getMessage('YLAB_MEETING_LIST_PARAMETERS_LIST_ID_NAME'),
      "TYPE"      =>  "STRING",
      "DEFAULT"   =>  "rooms_list"
    ),
    "ORM_NAME"    =>  array(
      "PARENT"    =>  "OPTIONS",
      "NAME"      =>  Loc::getMessage('YLAB_MEETING_LIST_PARAMETERS_ORM_NAME_NAME'),
      "TYPE"      =>  "STRING",
      "DEFAULT"   =>  "RoomTable"
    ),
    'COLUMN_FIELDS' => array(
      "PARENT" => "OPTIONS",
      "NAME" => Loc::getMessage('YLAB_MEETING_LIST_PARAMETERS_COLUMN_FIELDS_NAME'),
      "TYPE" => "STRING",
      "DEFAULT" => array("ID","NAME","ACTIVITY","INTEGRATION.NAME"),
      "MULTIPLE" => "Y",
      "ADDITIONAL_VALUES" => "Y"
    ),
    'FILTER_FIELDS' => array(
      "PARENT" => "OPTIONS",
      "NAME" => Loc::getMessage('YLAB_MEETING_LIST_PARAMETERS_FILTER_FIELDS_NAME'),
      "TYPE" => "STRING",
      "DEFAULT" => array("ID","NAME","ACTIVITY",),
      "MULTIPLE" => "Y",
      "ADDITIONAL_VALUES" => "Y"
    ),
  ),
);
?>



