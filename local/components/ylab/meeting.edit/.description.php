<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage('YLAB_MEETING_EDIT_EDIT_MEETING'),
    "DESCRIPTION" => Loc::getMessage('YLAB_MEETING_EDIT_COMPONENT_FROM'),
    "COMPLEX" => "N",
    "PATH" => [
        "ID" => 'ylab_local',
        "NAME" => 'Ylab',
    ],
];
