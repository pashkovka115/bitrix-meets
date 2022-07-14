<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
  die();
}

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
  "NAME" => Loc::getMessage('YLAB.MEETING.LIST.NAME'),
  "DESCRIPTION" => Loc::getMessage('YLAB.MEETING.LIST.DESCRIPTION'),
  "COMPLEX" => "N",
  "PATH" => [
    "ID" => 'ylab_local',
    "NAME" => 'Ylab',
  ],
];
